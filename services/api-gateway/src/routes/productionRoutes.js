import { Router } from 'express';
import {
  getWorkOrders,
  getWorkOrderByCode,
  createWorkOrder,
  getProductionLines,
  getLineWorkOrders,
  getDailyPerformance,
} from '../controllers/productionController.js';

const router = Router();

router.get('/work-orders', getWorkOrders);
router.get('/work-orders/:orderCode', getWorkOrderByCode);
router.post('/work-orders', createWorkOrder);
router.get('/lines', getProductionLines);
router.get('/lines/:lineCode/work-orders', getLineWorkOrders);
router.get('/performance/daily', getDailyPerformance);

export default router;
