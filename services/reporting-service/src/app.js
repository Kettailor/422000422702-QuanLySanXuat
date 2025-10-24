import express from 'express';
import morgan from 'morgan';
import protectedRouter, { publicRouter, internalRouter } from './routes/index.js';
import { authenticate, authenticateService } from './middleware/authMiddleware.js';
import { errorHandler } from './middleware/errorHandler.js';

const app = express();

app.use(express.json());
app.use(morgan('dev'));

app.use(publicRouter);
app.use('/internal', authenticateService, internalRouter);
app.use(authenticate);
app.use(protectedRouter);

app.use(errorHandler);

export default app;
