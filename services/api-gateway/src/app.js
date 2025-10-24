import express from 'express';
import morgan from 'morgan';
import { sessionMiddleware } from './config/session.js';
import { registerRoutes } from './routes/index.js';
import { errorHandler } from './middleware/errorHandler.js';

const app = express();

app.use(express.json());
app.use(morgan('dev'));
app.use(sessionMiddleware);

registerRoutes(app);
app.use(errorHandler);

export default app;
