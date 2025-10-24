import { asyncHandler } from '../utils/asyncHandler.js';
import { healthCheck } from '../config/mongo.js';
import { config } from '../config/env.js';

export const getHealth = asyncHandler(async (_req, res) => {
  await healthCheck();
  res.json({ status: 'ok', service: config.serviceName });
});
