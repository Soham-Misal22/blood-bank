import express from 'express';
import {
    getDonors,
    getDonor,
    createDonor,
    updateDonor,
    deleteDonor,
    getDonorsByBloodGroup,
} from '../controllers/donorController.js';
import { protect } from '../middleware/auth.js';
import { donorValidation, validate } from '../middleware/validation.js';

const router = express.Router();

router.route('/').get(getDonors).post(donorValidation, validate, createDonor);

router.route('/bloodgroup/:bloodGroup').get(getDonorsByBloodGroup);

router
    .route('/:id')
    .get(getDonor)
    .put(protect, donorValidation, validate, updateDonor)
    .delete(protect, deleteDonor);

export default router;
