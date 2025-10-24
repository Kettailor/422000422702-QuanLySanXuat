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

const asyncHandler = (fn) => (req, res, next) => Promise.resolve(fn(req, res, next)).catch(next);

app.use(express.json());
app.use(morgan('dev'));

app.get('/health', asyncHandler(async (_req, res) => {
  await pool.query('SELECT 1');
  res.json({ status: 'ok', service: process.env.SERVICE_NAME || 'production-service' });
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
