import express from 'express';
import healthRoutes from './healthRoutes.js';
import reportRoutes from './reportRoutes.js';
import eventRoutes from './eventRoutes.js';

export const publicRouter = express.Router();
publicRouter.use('/health', healthRoutes);

export const protectedRouter = express.Router();
protectedRouter.use('/reports', reportRoutes);

export const internalRouter = express.Router();
internalRouter.use('/events', eventRoutes);

export default protectedRouter;
