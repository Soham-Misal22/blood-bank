import Donor from '../models/Donor.js';

// @desc    Get all donors
// @route   GET /api/donors
// @access  Public
export const getDonors = async (req, res) => {
    try {
        const donors = await Donor.find().sort({ createdAt: -1 });

        res.status(200).json({
            success: true,
            count: donors.length,
            data: donors,
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: error.message,
        });
    }
};

// @desc    Get single donor
// @route   GET /api/donors/:id
// @access  Public
export const getDonor = async (req, res) => {
    try {
        const donor = await Donor.findById(req.params.id);

        if (!donor) {
            return res.status(404).json({
                success: false,
                message: 'Donor not found',
            });
        }

        res.status(200).json({
            success: true,
            data: donor,
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: error.message,
        });
    }
};

// @desc    Create new donor
// @route   POST /api/donors
// @access  Public
export const createDonor = async (req, res) => {
    try {
        const donor = await Donor.create(req.body);

        res.status(201).json({
            success: true,
            message: 'Donor registered successfully!',
            data: donor,
        });
    } catch (error) {
        res.status(400).json({
            success: false,
            message: error.message,
        });
    }
};

// @desc    Update donor
// @route   PUT /api/donors/:id
// @access  Private
export const updateDonor = async (req, res) => {
    try {
        const donor = await Donor.findByIdAndUpdate(req.params.id, req.body, {
            new: true,
            runValidators: true,
        });

        if (!donor) {
            return res.status(404).json({
                success: false,
                message: 'Donor not found',
            });
        }

        res.status(200).json({
            success: true,
            message: 'Donor updated successfully',
            data: donor,
        });
    } catch (error) {
        res.status(400).json({
            success: false,
            message: error.message,
        });
    }
};

// @desc    Delete donor
// @route   DELETE /api/donors/:id
// @access  Private
export const deleteDonor = async (req, res) => {
    try {
        const donor = await Donor.findByIdAndDelete(req.params.id);

        if (!donor) {
            return res.status(404).json({
                success: false,
                message: 'Donor not found',
            });
        }

        res.status(200).json({
            success: true,
            message: 'Donor deleted successfully',
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: error.message,
        });
    }
};

// @desc    Get donors by blood group
// @route   GET /api/donors/bloodgroup/:bloodGroup
// @access  Public
export const getDonorsByBloodGroup = async (req, res) => {
    try {
        const donors = await Donor.find({
            bloodGroup: req.params.bloodGroup.toUpperCase(),
            status: 'active',
        });

        res.status(200).json({
            success: true,
            count: donors.length,
            data: donors,
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: error.message,
        });
    }
};
