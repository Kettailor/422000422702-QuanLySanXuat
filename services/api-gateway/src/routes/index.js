import express from 'express';
import { authenticate } from '../middleware/authMiddleware.js';
import { getHealth } from '../controllers/healthController.js';
import authRoutes from './authRoutes.js';
import productionRoutes from './productionRoutes.js';
import reportRoutes from './reportRoutes.js';

export const registerRoutes = (app) => {
  const router = express.Router();

  router.get('/health', getHealth);
  router.use('/auth', authRoutes);
  router.use('/api/production', authenticate, productionRoutes);
  router.use('/api/reports', authenticate, reportRoutes);

  app.use(router);
};

export default registerRoutes;
