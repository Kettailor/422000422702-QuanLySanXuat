import { Router } from 'express';
import { getSummaryReport, getOeeReport } from '../controllers/reportController.js';

const router = Router();

router.get('/summary', getSummaryReport);
router.get('/oee', getOeeReport);

export default router;
