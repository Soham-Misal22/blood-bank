import mongoose from 'mongoose';

const receiverSchema = new mongoose.Schema({
    name: {
        type: String,
        required: [true, 'Please provide receiver name'],
        trim: true,
    },
    email: {
        type: String,
        required: [true, 'Please provide email'],
        trim: true,
        lowercase: true,
        match: [
            /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/,
            'Please provide a valid email',
        ],
    },
    contact: {
        type: String,
        required: [true, 'Please provide contact number'],
        trim: true,
    },
    bloodGroup: {
        type: String,
        required: [true, 'Please provide blood group'],
        enum: ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
    },
    hospital: {
        type: String,
        required: [true, 'Please provide hospital name'],
        trim: true,
    },
    address: {
        type: String,
        trim: true,
    },
    medicalCondition: {
        type: String,
        trim: true,
    },
    urgencyLevel: {
        type: String,
        required: [true, 'Please specify urgency level'],
        enum: ['critical', 'urgent', 'normal'],
        default: 'normal',
    },
    unitsNeeded: {
        type: Number,
        required: [true, 'Please specify units needed'],
        min: [1, 'At least 1 unit is required'],
        max: [10, 'Cannot request more than 10 units'],
    },
    requestDate: {
        type: Date,
        default: Date.now,
    },
    status: {
        type: String,
        enum: ['pending', 'approved', 'fulfilled', 'cancelled'],
        default: 'pending',
    },
    notes: {
        type: String,
        trim: true,
    },
    createdAt: {
        type: Date,
        default: Date.now,
    },
    updatedAt: {
        type: Date,
        default: Date.now,
    },
});

// Update the updatedAt field before saving
receiverSchema.pre('save', function (next) {
    this.updatedAt = Date.now();
    next();
});

// Create indexes for faster queries
receiverSchema.index({ email: 1 });
receiverSchema.index({ bloodGroup: 1 });
receiverSchema.index({ status: 1 });
receiverSchema.index({ urgencyLevel: 1 });

export default mongoose.model('Receiver', receiverSchema);
