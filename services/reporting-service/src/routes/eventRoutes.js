import { Router } from 'express';
import { receiveEvent } from '../controllers/eventController.js';

const router = Router();

router.post('/', receiveEvent);

export default router;
