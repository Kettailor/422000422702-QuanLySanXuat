import { pool } from '../config/postgres.js';

export const findDailyPerformance = async () => {
  const result = await pool.query(`
    SELECT
      s.shift_date AS "shiftDate",
      s.shift_name AS "shiftName",
      SUM(lsm.planned_output) AS "plannedOutput",
      SUM(lsm.actual_output) AS "actualOutput",
      SUM(lsm.downtime_minutes) AS "downtimeMinutes"
    FROM shifts s
    JOIN line_shift_metrics lsm ON lsm.shift_id = s.id
    WHERE s.shift_date BETWEEN CURRENT_DATE - INTERVAL '2 day' AND CURRENT_DATE
    GROUP BY s.shift_date, s.shift_name
    ORDER BY s.shift_date DESC, s.shift_name
  `);

  return result.rows;
};
