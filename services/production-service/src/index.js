import express from 'express';
import morgan from 'morgan';
import pkg from 'pg';

const { Pool } = pkg;

const app = express();
const port = process.env.PORT || 4000;

const pool = new Pool({
  host: process.env.POSTGRES_HOST || 'localhost',
  port: Number(process.env.POSTGRES_PORT || 5432),
  database: process.env.POSTGRES_DB || 'production',
  user: process.env.POSTGRES_USER || 'prod_admin',
  password: process.env.POSTGRES_PASSWORD || 'prod_admin',
});

const elevatedRoles = ['system_admin', 'board'];
const validAssignmentRoles = new Set(['manager', 'warehouse', 'production']);

const asyncHandler = (fn) => (req, res, next) => Promise.resolve(fn(req, res, next)).catch(next);

app.use(express.json());
app.use(morgan('dev'));

app.use((req, _res, next) => {
  req.userRole = (req.header('x-user-role') || 'employee').toLowerCase();
  const employeeIdHeader = req.header('x-employee-id');
  req.employeeId = employeeIdHeader ? Number(employeeIdHeader) : null;
  next();
});

const requireRoles = (...roles) => (req, res, next) => {
  if (!roles.includes(req.userRole)) {
    res.status(403).json({ message: 'Bạn không có quyền thực hiện thao tác này' });
    return;
  }

  next();
};

const canManageWorkshop = async (req, workshopId) => {
  if (elevatedRoles.includes(req.userRole)) {
    return true;
  }

  if (req.userRole === 'workshop_manager' && req.employeeId) {
    const managerResult = await pool.query(
      `SELECT 1 FROM workshop_assignments
        WHERE workshop_id = $1 AND employee_id = $2 AND assignment_role = 'manager'
        LIMIT 1`,
      [workshopId, req.employeeId],
    );

    return managerResult.rowCount > 0;
  }

  return false;
};

app.get('/health', asyncHandler(async (_req, res) => {
  await pool.query('SELECT 1');
  res.json({ status: 'ok', service: process.env.SERVICE_NAME || 'production-service' });
}));

app.get('/employees', asyncHandler(async (req, res) => {
  const search = req.query.search ? `%${req.query.search}%` : null;

  const employees = await pool.query(
    `SELECT
        id,
        employee_code AS employeeCode,
        full_name AS fullName,
        title,
        system_role AS systemRole,
        status
      FROM employees
      WHERE ($1::TEXT IS NULL OR full_name ILIKE $1)
      ORDER BY full_name ASC
      LIMIT 200`,
    [search],
  );

  res.json(employees.rows);
}));

app.get('/workshops', asyncHandler(async (req, res) => {
  const search = req.query.search ? `%${req.query.search}%` : null;

  const workshops = await pool.query(
    `SELECT
        w.id,
        w.code,
        w.name,
        w.location,
        w.status,
        manager.employee_id AS managerId,
        manager.employee_code AS managerCode,
        manager.full_name AS managerName,
        COALESCE(warehouse.count, 0) AS warehouseCount,
        COALESCE(production.count, 0) AS productionCount
      FROM workshops w
      LEFT JOIN LATERAL (
        SELECT e.id AS employee_id, e.employee_code, e.full_name
        FROM workshop_assignments wa
        JOIN employees e ON e.id = wa.employee_id
        WHERE wa.workshop_id = w.id AND wa.assignment_role = 'manager'
        LIMIT 1
      ) AS manager ON TRUE
      LEFT JOIN LATERAL (
        SELECT COUNT(*)::INT AS count
        FROM workshop_assignments wa
        WHERE wa.workshop_id = w.id AND wa.assignment_role = 'warehouse'
      ) AS warehouse ON TRUE
      LEFT JOIN LATERAL (
        SELECT COUNT(*)::INT AS count
        FROM workshop_assignments wa
        WHERE wa.workshop_id = w.id AND wa.assignment_role = 'production'
      ) AS production ON TRUE
      WHERE ($1::TEXT IS NULL OR w.name ILIKE $1 OR w.code ILIKE $1)
      ORDER BY w.code`,
    [search],
  );

  res.json(workshops.rows);
}));

app.post('/workshops', requireRoles('system_admin', 'board'), asyncHandler(async (req, res) => {
  const { code, name, location, status = 'active' } = req.body;

  const created = await pool.query(
    `INSERT INTO workshops (code, name, location, status)
      VALUES ($1, $2, $3, COALESCE($4, 'active'))
      ON CONFLICT (code) DO NOTHING
      RETURNING id, code, name, location, status`,
    [code, name, location, status],
  );

  if (created.rowCount === 0) {
    res.status(409).json({ message: 'Mã xưởng đã tồn tại' });
    return;
  }

  res.status(201).json(created.rows[0]);
}));

app.get('/workshops/:id', asyncHandler(async (req, res) => {
  const workshopId = Number(req.params.id);

  const workshop = await pool.query(
    `SELECT id, code, name, location, status FROM workshops WHERE id = $1`,
    [workshopId],
  );

  if (workshop.rowCount === 0) {
    res.status(404).json({ message: 'Không tìm thấy xưởng' });
    return;
  }

  const assignments = await pool.query(
    `SELECT
        wa.id,
        wa.assignment_role AS assignmentRole,
        wa.assigned_at AS assignedAt,
        e.id AS employeeId,
        e.employee_code AS employeeCode,
        e.full_name AS fullName,
        e.title,
        e.system_role AS systemRole
      FROM workshop_assignments wa
      JOIN employees e ON e.id = wa.employee_id
      WHERE wa.workshop_id = $1
        AND ($2::TEXT IS NULL OR e.full_name ILIKE $2)
      ORDER BY wa.assignment_role, e.full_name`,
    [workshopId, req.query.search ? `%${req.query.search}%` : null],
  );

  res.json({ ...workshop.rows[0], assignments: assignments.rows });
}));

app.put('/workshops/:id', asyncHandler(async (req, res) => {
  const workshopId = Number(req.params.id);

  if (!(await canManageWorkshop(req, workshopId))) {
    res.status(403).json({ message: 'Bạn không có quyền chỉnh sửa thông tin xưởng này' });
    return;
  }

  const { name, location, status } = req.body;

  const updated = await pool.query(
    `UPDATE workshops
      SET name = COALESCE($2, name),
          location = COALESCE($3, location),
          status = COALESCE($4, status)
      WHERE id = $1
      RETURNING id, code, name, location, status`,
    [workshopId, name, location, status],
  );

  if (updated.rowCount === 0) {
    res.status(404).json({ message: 'Không tìm thấy xưởng' });
    return;
  }

  res.json(updated.rows[0]);
}));

app.get('/workshops/:id/assignments', asyncHandler(async (req, res) => {
  const workshopId = Number(req.params.id);
  const search = req.query.search ? `%${req.query.search}%` : null;

  const assignments = await pool.query(
    `SELECT
        wa.id,
        wa.assignment_role AS assignmentRole,
        wa.assigned_at AS assignedAt,
        e.id AS employeeId,
        e.employee_code AS employeeCode,
        e.full_name AS fullName,
        e.title,
        e.system_role AS systemRole
      FROM workshop_assignments wa
      JOIN employees e ON e.id = wa.employee_id
      WHERE wa.workshop_id = $1
        AND ($2::TEXT IS NULL OR e.full_name ILIKE $2)
      ORDER BY wa.assignment_role, e.full_name`,
    [workshopId, search],
  );

  res.json(assignments.rows);
}));

app.post('/workshops/:id/assignments', requireRoles('system_admin', 'board'), asyncHandler(async (req, res) => {
  const workshopId = Number(req.params.id);
  const { employeeId, assignmentRole } = req.body;

  if (!validAssignmentRoles.has(assignmentRole)) {
    res.status(400).json({ message: 'Vai trò phân công không hợp lệ' });
    return;
  }

  const workshop = await pool.query('SELECT 1 FROM workshops WHERE id = $1', [workshopId]);
  if (workshop.rowCount === 0) {
    res.status(404).json({ message: 'Không tìm thấy xưởng' });
    return;
  }

  const employee = await pool.query('SELECT 1 FROM employees WHERE id = $1', [employeeId]);
  if (employee.rowCount === 0) {
    res.status(404).json({ message: 'Không tìm thấy nhân viên' });
    return;
  }

  if (assignmentRole === 'manager') {
    await pool.query(
      `DELETE FROM workshop_assignments
        WHERE workshop_id = $1 AND assignment_role = 'manager' AND employee_id <> $2`,
      [workshopId, employeeId],
    );
  }

  const assignment = await pool.query(
    `INSERT INTO workshop_assignments (workshop_id, employee_id, assignment_role)
      VALUES ($1, $2, $3)
      ON CONFLICT (workshop_id, employee_id) DO UPDATE
        SET assignment_role = EXCLUDED.assignment_role, assigned_at = NOW()
      RETURNING id, workshop_id AS workshopId, employee_id AS employeeId, assignment_role AS assignmentRole, assigned_at AS assignedAt`,
    [workshopId, employeeId, assignmentRole],
  );

  res.status(201).json(assignment.rows[0]);
}));

app.get('/work-orders', asyncHandler(async (_req, res) => {
  const result = await pool.query(
    `SELECT
        wo.order_code AS orderCode,
        pl.code AS lineCode,
        pl.name AS lineName,
        pl.department,
        wo.product_code AS productCode,
        wo.planned_quantity AS plannedQuantity,
        wo.completed_quantity AS completedQuantity,
        wo.scrap_quantity AS scrapQuantity,
        wo.status,
        wo.due_time AS dueTime,
        wo.created_at AS createdAt
      FROM work_orders wo
      JOIN production_lines pl ON pl.id = wo.line_id
      ORDER BY wo.due_time ASC
      LIMIT 100`
  );

  res.json(result.rows);
}));

app.get('/work-orders/:orderCode', asyncHandler(async (req, res) => {
  const { orderCode } = req.params;
  const result = await pool.query(
    `SELECT
        wo.order_code AS orderCode,
        pl.code AS lineCode,
        pl.name AS lineName,
        wo.product_code AS productCode,
        wo.planned_quantity AS plannedQuantity,
        wo.completed_quantity AS completedQuantity,
        wo.scrap_quantity AS scrapQuantity,
        wo.status,
        wo.due_time AS dueTime,
        wo.created_at AS createdAt
      FROM work_orders wo
      JOIN production_lines pl ON pl.id = wo.line_id
      WHERE wo.order_code = $1`,
    [orderCode]
  );

  if (result.rowCount === 0) {
    res.status(404).json({ message: 'Work order not found' });
    return;
  }

  res.json(result.rows[0]);
}));

app.post('/work-orders', asyncHandler(async (req, res) => {
  const {
    orderCode,
    lineCode,
    productCode,
    plannedQuantity,
    completedQuantity = 0,
    scrapQuantity = 0,
    status,
    dueTime,
  } = req.body;

  const lineResult = await pool.query('SELECT id FROM production_lines WHERE code = $1', [lineCode]);
  if (lineResult.rowCount === 0) {
    res.status(400).json({ message: 'Invalid line code' });
    return;
  }

  const insertResult = await pool.query(
    `INSERT INTO work_orders (
        order_code,
        line_id,
        product_code,
        planned_quantity,
        completed_quantity,
        scrap_quantity,
        status,
        due_time
      ) VALUES ($1, $2, $3, $4, $5, $6, $7, COALESCE($8, NOW()))
      ON CONFLICT (order_code) DO NOTHING
      RETURNING id`,
    [
      orderCode,
      lineResult.rows[0].id,
      productCode,
      plannedQuantity,
      completedQuantity,
      scrapQuantity,
      status,
      dueTime,
    ]
  );

  if (insertResult.rowCount === 0) {
    res.status(409).json({ message: 'Work order already exists' });
    return;
  }

  const insertedOrder = await pool.query(
    `SELECT
        wo.order_code AS orderCode,
        pl.code AS lineCode,
        pl.name AS lineName,
        wo.product_code AS productCode,
        wo.planned_quantity AS plannedQuantity,
        wo.completed_quantity AS completedQuantity,
        wo.scrap_quantity AS scrapQuantity,
        wo.status,
        wo.due_time AS dueTime,
        wo.created_at AS createdAt
      FROM work_orders wo
      JOIN production_lines pl ON pl.id = wo.line_id
      WHERE wo.order_code = $1`,
    [orderCode]
  );

  res.status(201).json(insertedOrder.rows[0]);
}));

app.get('/production-lines', asyncHandler(async (_req, res) => {
  const result = await pool.query(
    `WITH todays_shift AS (
        SELECT id FROM shifts WHERE shift_date = CURRENT_DATE
      )
      SELECT
        pl.code AS lineCode,
        pl.name AS lineName,
        pl.department,
        pl.status,
        COALESCE(SUM(lsm.planned_output), 0) AS plannedOutput,
        COALESCE(SUM(lsm.actual_output), 0) AS actualOutput,
        COALESCE(SUM(lsm.downtime_minutes), 0) AS downtimeMinutes,
        COUNT(CASE WHEN wo.status IN ('planned', 'in_progress') THEN 1 END) AS activeWorkOrders
      FROM production_lines pl
      LEFT JOIN line_shift_metrics lsm ON lsm.line_id = pl.id AND lsm.shift_id IN (SELECT id FROM todays_shift)
      LEFT JOIN work_orders wo ON wo.line_id = pl.id
      GROUP BY pl.id
      ORDER BY pl.code`
  );

  res.json(result.rows);
}));

app.get('/production-lines/:lineCode/work-orders', asyncHandler(async (req, res) => {
  const { lineCode } = req.params;
  const result = await pool.query(
    `SELECT
        wo.order_code AS orderCode,
        wo.product_code AS productCode,
        wo.planned_quantity AS plannedQuantity,
        wo.completed_quantity AS completedQuantity,
        wo.scrap_quantity AS scrapQuantity,
        wo.status,
        wo.due_time AS dueTime
      FROM work_orders wo
      JOIN production_lines pl ON pl.id = wo.line_id
      WHERE pl.code = $1
      ORDER BY wo.due_time ASC`,
    [lineCode]
  );

  res.json(result.rows);
}));

app.get('/performance/daily', asyncHandler(async (_req, res) => {
  const result = await pool.query(
    `SELECT
        s.shift_date AS shiftDate,
        s.shift_name AS shiftName,
        SUM(lsm.planned_output) AS plannedOutput,
        SUM(lsm.actual_output) AS actualOutput,
        SUM(lsm.downtime_minutes) AS downtimeMinutes
      FROM shifts s
      JOIN line_shift_metrics lsm ON lsm.shift_id = s.id
      WHERE s.shift_date BETWEEN CURRENT_DATE - INTERVAL '2 day' AND CURRENT_DATE
      GROUP BY s.shift_date, s.shift_name
      ORDER BY s.shift_date DESC, s.shift_name`
  );

  res.json(result.rows);
}));

app.use((err, _req, res, _next) => {
  console.error(err.message);
  res.status(500).json({ message: 'Production service error', details: err.message });
});

app.listen(port, () => {
  console.log(`Production Service listening on port ${port}`);
});
