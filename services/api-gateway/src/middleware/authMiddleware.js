import jwt from 'jsonwebtoken';
import { config } from '../config/env.js';

const extractToken = (req) => {
  if (req.session?.token) {
    return req.session.token;
  }

  const header = req.headers.authorization;
  if (!header) {
    return null;
  }

  const [scheme, token] = header.split(' ');
  if (scheme?.toLowerCase() !== 'bearer') {
    return null;
  }

  return token;
};

export const authenticate = (req, res, next) => {
  const token = extractToken(req);
  if (!token) {
    res.status(401).json({ message: 'Authentication required' });
    return;
  }

  try {
    const payload = jwt.verify(token, config.jwt.secret);
    req.user = payload;
    req.token = token;
    next();
  } catch (error) {
    res.status(401).json({ message: 'Invalid or expired token' });
  }
};
