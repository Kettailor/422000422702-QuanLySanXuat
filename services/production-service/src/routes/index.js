import express from 'express';
import workOrderRoutes from './workOrderRoutes.js';
import productionLineRoutes from './productionLineRoutes.js';
import performanceRoutes from './performanceRoutes.js';
import healthRoutes from './healthRoutes.js';

export const publicRouter = express.Router();
publicRouter.use('/health', healthRoutes);

export const protectedRouter = express.Router();
protectedRouter.use('/work-orders', workOrderRoutes);
protectedRouter.use('/production-lines', productionLineRoutes);
protectedRouter.use('/performance', performanceRoutes);

export default protectedRouter;
