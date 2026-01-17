import BloodInventory from '../models/BloodInventory.js';

// @desc    Get all blood inventory
// @route   GET /api/inventory
// @access  Public
export const getInventory = async (req, res) => {
    try {
        const inventory = await BloodInventory.find().sort({ bloodType: 1 });

        res.status(200).json({
            success: true,
            count: inventory.length,
            data: inventory,
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: error.message,
        });
    }
};

// @desc    Get inventory by blood type
// @route   GET /api/inventory/:bloodType
// @access  Public
export const getInventoryByBloodType = async (req, res) => {
    try {
        const inventory = await BloodInventory.findOne({
            bloodType: req.params.bloodType.toUpperCase(),
        });

        if (!inventory) {
            return res.status(404).json({
                success: false,
                message: 'Blood type not found in inventory',
            });
        }

        res.status(200).json({
            success: true,
            data: inventory,
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: error.message,
        });
    }
};

// @desc    Update blood inventory
// @route   PUT /api/inventory/:bloodType
// @access  Private
export const updateInventory = async (req, res) => {
    try {
        const { unitsAvailable } = req.body;

        const inventory = await BloodInventory.findOne({
            bloodType: req.params.bloodType.toUpperCase(),
        });

        if (!inventory) {
            return res.status(404).json({
                success: false,
                message: 'Blood type not found in inventory',
            });
        }

        inventory.unitsAvailable = unitsAvailable;
        await inventory.save();

        res.status(200).json({
            success: true,
            message: 'Inventory updated successfully',
            data: inventory,
        });
    } catch (error) {
        res.status(400).json({
            success: false,
            message: error.message,
        });
    }
};

// @desc    Add units to inventory
// @route   POST /api/inventory/:bloodType/add
// @access  Private
export const addUnits = async (req, res) => {
    try {
        const { units } = req.body;

        const inventory = await BloodInventory.updateInventory(
            req.params.bloodType.toUpperCase(),
            units
        );

        res.status(200).json({
            success: true,
            message: `Added ${units} units successfully`,
            data: inventory,
        });
    } catch (error) {
        res.status(400).json({
            success: false,
            message: error.message,
        });
    }
};

// @desc    Remove units from inventory
// @route   POST /api/inventory/:bloodType/remove
// @access  Private
export const removeUnits = async (req, res) => {
    try {
        const { units } = req.body;

        const inventory = await BloodInventory.updateInventory(
            req.params.bloodType.toUpperCase(),
            -units
        );

        res.status(200).json({
            success: true,
            message: `Removed ${units} units successfully`,
            data: inventory,
        });
    } catch (error) {
        res.status(400).json({
            success: false,
            message: error.message,
        });
    }
};

// @desc    Get critical inventory (low stock)
// @route   GET /api/inventory/status/critical
// @access  Public
export const getCriticalInventory = async (req, res) => {
    try {
        const inventory = await BloodInventory.find({
            status: { $in: ['Critical', 'Limited'] },
        }).sort({ unitsAvailable: 1 });

        res.status(200).json({
            success: true,
            count: inventory.length,
            data: inventory,
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: error.message,
        });
    }
};
