import { config } from '../config/env.js';

export const getHealth = (_req, res) => {
  res.json({ status: 'ok', service: config.serviceName });
};
