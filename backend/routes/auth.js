import express from 'express';
import {
    register,
    login,
    getMe,
    logout,
} from '../controllers/authController.js';
import { protect } from '../middleware/auth.js';
import { loginValidation, adminValidation, validate } from '../middleware/validation.js';

const router = express.Router();

router.post('/register', adminValidation, validate, register);
router.post('/login', loginValidation, validate, login);
router.get('/me', protect, getMe);
router.get('/logout', protect, logout);

export default router;
