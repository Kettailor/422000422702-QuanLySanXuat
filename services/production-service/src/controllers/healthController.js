import { healthCheck } from '../config/postgres.js';
import { config } from '../config/env.js';
import { asyncHandler } from '../utils/asyncHandler.js';

export const getHealth = asyncHandler(async (_req, res) => {
  await healthCheck();
  res.json({ status: 'ok', service: config.serviceName });
});
