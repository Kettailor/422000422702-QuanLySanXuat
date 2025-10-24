import { Router } from 'express';
import { getWorkOrders, getWorkOrderByCode, createWorkOrder } from '../controllers/workOrderController.js';

const router = Router();

router.get('/', getWorkOrders);
router.get('/:orderCode', getWorkOrderByCode);
router.post('/', createWorkOrder);

export default router;
