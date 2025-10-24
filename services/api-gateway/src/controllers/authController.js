import jwt from 'jsonwebtoken';
import crypto from 'crypto';
import { config } from '../config/env.js';

const timingSafeEqual = (a, b) => {
  const aBuffer = Buffer.from(a);
  const bBuffer = Buffer.from(b);

  if (aBuffer.length !== bBuffer.length) {
    return false;
  }

  return crypto.timingSafeEqual(aBuffer, bBuffer);
};

export const login = (req, res) => {
  const { username, password } = req.body || {};

  if (!username || !password) {
    res.status(400).json({ message: 'Username and password are required' });
    return;
  }

  const validUser =
    timingSafeEqual(username, config.auth.username) && timingSafeEqual(password, config.auth.password);

  if (!validUser) {
    res.status(401).json({ message: 'Invalid credentials' });
    return;
  }

  const payload = { username, role: 'admin' };
  const token = jwt.sign(payload, config.jwt.secret, { expiresIn: config.jwt.expiresIn });

  req.session.token = token;
  req.session.user = payload;

  res.json({
    message: 'Login successful',
    token,
    user: payload,
  });
};

export const logout = (req, res, next) => {
  req.session.destroy((err) => {
    if (err) {
      next(err);
      return;
    }
    res.json({ message: 'Logged out' });
  });
};

export const getSession = (req, res) => {
  if (!req.session?.user || !req.session?.token) {
    res.status(401).json({ message: 'No active session' });
    return;
  }

  res.json({
    user: req.session.user,
    token: req.session.token,
  });
};
