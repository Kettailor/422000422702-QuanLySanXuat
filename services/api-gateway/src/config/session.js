import session from 'express-session';
import { config } from './env.js';

export const sessionConfig = {
  name: config.session.name,
  secret: config.session.secret,
  resave: false,
  saveUninitialized: false,
  cookie: {
    httpOnly: true,
    sameSite: 'lax',
    maxAge: config.session.maxAge,
  },
};

export const sessionMiddleware = session(sessionConfig);
