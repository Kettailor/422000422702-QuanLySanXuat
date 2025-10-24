import jwt from 'jsonwebtoken';
import { config } from '../config/env.js';

export const signServiceToken = (payload) =>
  jwt.sign({ ...payload, role: payload.role || 'service' }, config.jwt.secret, { expiresIn: '5m' });
