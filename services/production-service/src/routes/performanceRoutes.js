import { Router } from 'express';
import { getDailyPerformance } from '../controllers/performanceController.js';

const router = Router();

router.get('/daily', getDailyPerformance);

export default router;
