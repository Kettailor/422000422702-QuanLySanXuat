import { Router } from 'express';
import { getProductionLines, getLineWorkOrders } from '../controllers/productionLineController.js';

const router = Router();

router.get('/', getProductionLines);
router.get('/:lineCode/work-orders', getLineWorkOrders);

export default router;
