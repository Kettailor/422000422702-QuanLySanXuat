import express from 'express';
import cors from 'cors';
import morgan from 'morgan';
import { sessionMiddleware } from './config/session.js';
import { registerRoutes } from './routes/index.js';
import { errorHandler } from './middleware/errorHandler.js';
import { config } from './config/env.js';

const app = express();

app.use(express.json());
app.use(morgan('dev'));
const allowedOrigins = config.corsOrigins.length > 0 ? config.corsOrigins : true;
app.use(
  cors({
    origin: allowedOrigins,
    credentials: true,
  })
);
app.use(sessionMiddleware);

registerRoutes(app);
app.use(errorHandler);

export default app;
