import { pool } from '../config/postgres.js';

const baseSelect = `
  SELECT
    wo.order_code AS "orderCode",
    pl.code AS "lineCode",
    pl.name AS "lineName",
    pl.department AS "department",
    wo.product_code AS "productCode",
    wo.planned_quantity AS "plannedQuantity",
    wo.completed_quantity AS "completedQuantity",
    wo.scrap_quantity AS "scrapQuantity",
    wo.status AS "status",
    wo.due_time AS "dueTime",
    wo.created_at AS "createdAt"
  FROM work_orders wo
  JOIN production_lines pl ON pl.id = wo.line_id
`;

export const findAll = async () => {
  const result = await pool.query(`${baseSelect} ORDER BY wo.due_time ASC LIMIT 100`);
  return result.rows;
};

export const findByCode = async (orderCode) => {
  const result = await pool.query(`${baseSelect} WHERE wo.order_code = $1`, [orderCode]);
  return result.rows[0] || null;
};

export const insert = async ({
  orderCode,
  lineId,
  productCode,
  plannedQuantity,
  completedQuantity,
  scrapQuantity,
  status,
  dueTime,
}) => {
  const result = await pool.query(
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
    [orderCode, lineId, productCode, plannedQuantity, completedQuantity, scrapQuantity, status, dueTime]
  );

  return result.rows[0] || null;
};
