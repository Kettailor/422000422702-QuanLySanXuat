import { pool } from '../config/postgres.js';

export const findLineByCode = async (lineCode) => {
  const result = await pool.query('SELECT id FROM production_lines WHERE code = $1', [lineCode]);
  return result.rows[0] || null;
};

export const findAll = async () => {
  const result = await pool.query(`
    WITH todays_shift AS (
      SELECT id FROM shifts WHERE shift_date = CURRENT_DATE
    )
    SELECT
      pl.code AS "lineCode",
      pl.name AS "lineName",
      pl.department AS "department",
      pl.status AS "status",
      COALESCE(SUM(lsm.planned_output), 0) AS "plannedOutput",
      COALESCE(SUM(lsm.actual_output), 0) AS "actualOutput",
      COALESCE(SUM(lsm.downtime_minutes), 0) AS "downtimeMinutes",
      COUNT(CASE WHEN wo.status IN ('planned', 'in_progress') THEN 1 END) AS "activeWorkOrders"
    FROM production_lines pl
    LEFT JOIN line_shift_metrics lsm ON lsm.line_id = pl.id AND lsm.shift_id IN (SELECT id FROM todays_shift)
    LEFT JOIN work_orders wo ON wo.line_id = pl.id
    GROUP BY pl.id
    ORDER BY pl.code
  `);

  return result.rows;
};

export const findWorkOrdersForLine = async (lineCode) => {
  const result = await pool.query(
    `SELECT
        wo.order_code AS "orderCode",
        wo.product_code AS "productCode",
        wo.planned_quantity AS "plannedQuantity",
        wo.completed_quantity AS "completedQuantity",
        wo.scrap_quantity AS "scrapQuantity",
        wo.status AS "status",
        wo.due_time AS "dueTime"
      FROM work_orders wo
      JOIN production_lines pl ON pl.id = wo.line_id
      WHERE pl.code = $1
      ORDER BY wo.due_time ASC`,
    [lineCode]
  );

  return result.rows;
};
