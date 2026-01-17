import Receiver from '../models/Receiver.js';

// @desc    Get all receivers
// @route   GET /api/receivers
// @access  Public
export const getReceivers = async (req, res) => {
    try {
        const receivers = await Receiver.find().sort({ createdAt: -1 });

        res.status(200).json({
            success: true,
            count: receivers.length,
            data: receivers,
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: error.message,
        });
    }
};

// @desc    Get single receiver
// @route   GET /api/receivers/:id
// @access  Public
export const getReceiver = async (req, res) => {
    try {
        const receiver = await Receiver.findById(req.params.id);

        if (!receiver) {
            return res.status(404).json({
                success: false,
                message: 'Receiver not found',
            });
        }

        res.status(200).json({
            success: true,
            data: receiver,
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: error.message,
        });
    }
};

// @desc    Create new receiver/blood request
// @route   POST /api/receivers
// @access  Public
export const createReceiver = async (req, res) => {
    try {
        const receiver = await Receiver.create(req.body);

        res.status(201).json({
            success: true,
            message: 'Blood request submitted successfully!',
            data: receiver,
        });
    } catch (error) {
        res.status(400).json({
            success: false,
            message: error.message,
        });
    }
};

// @desc    Update receiver
// @route   PUT /api/receivers/:id
// @access  Private
export const updateReceiver = async (req, res) => {
    try {
        const receiver = await Receiver.findByIdAndUpdate(req.params.id, req.body, {
            new: true,
            runValidators: true,
        });

        if (!receiver) {
            return res.status(404).json({
                success: false,
                message: 'Receiver not found',
            });
        }

        res.status(200).json({
            success: true,
            message: 'Request updated successfully',
            data: receiver,
        });
    } catch (error) {
        res.status(400).json({
            success: false,
            message: error.message,
        });
    }
};

// @desc    Delete receiver
// @route   DELETE /api/receivers/:id
// @access  Private
export const deleteReceiver = async (req, res) => {
    try {
        const receiver = await Receiver.findByIdAndDelete(req.params.id);

        if (!receiver) {
            return res.status(404).json({
                success: false,
                message: 'Receiver not found',
            });
        }

        res.status(200).json({
            success: true,
            message: 'Request deleted successfully',
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: error.message,
        });
    }
};

// @desc    Get receivers by urgency
// @route   GET /api/receivers/urgency/:level
// @access  Private
export const getReceiversByUrgency = async (req, res) => {
    try {
        const receivers = await Receiver.find({
            urgencyLevel: req.params.level,
            status: 'pending',
        }).sort({ requestDate: 1 });

        res.status(200).json({
            success: true,
            count: receivers.length,
            data: receivers,
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: error.message,
        });
    }
};
