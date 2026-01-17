import express from 'express';
import {
    getReceivers,
    getReceiver,
    createReceiver,
    updateReceiver,
    deleteReceiver,
    getReceiversByUrgency,
} from '../controllers/receiverController.js';
import { protect } from '../middleware/auth.js';
import { receiverValidation, validate } from '../middleware/validation.js';

const router = express.Router();

router.route('/').get(getReceivers).post(receiverValidation, validate, createReceiver);

router.route('/urgency/:level').get(protect, getReceiversByUrgency);

router
    .route('/:id')
    .get(getReceiver)
    .put(protect, receiverValidation, validate, updateReceiver)
    .delete(protect, deleteReceiver);

export default router;
