import { Router } from 'express';
import { login, logout, getSession } from '../controllers/authController.js';
import { authenticate } from '../middleware/authMiddleware.js';

const router = Router();

router.post('/login', login);
router.post('/logout', authenticate, logout);
router.get('/session', authenticate, getSession);

export default router;
