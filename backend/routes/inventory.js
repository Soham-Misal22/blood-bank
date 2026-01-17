import express from 'express';
import {
    getInventory,
    getInventoryByBloodType,
    updateInventory,
    addUnits,
    removeUnits,
    getCriticalInventory,
} from '../controllers/inventoryController.js';
import { protect } from '../middleware/auth.js';

const router = express.Router();

router.route('/').get(getInventory);

router.route('/status/critical').get(getCriticalInventory);

router.route('/:bloodType').get(getInventoryByBloodType).put(protect, updateInventory);

router.route('/:bloodType/add').post(protect, addUnits);

router.route('/:bloodType/remove').post(protect, removeUnits);

export default router;
