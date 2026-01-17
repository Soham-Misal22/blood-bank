import mongoose from 'mongoose';

const donorSchema = new mongoose.Schema({
    name: {
        type: String,
        required: [true, 'Please provide donor name'],
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
    dob: {
        type: Date,
        required: [true, 'Please provide date of birth'],
    },
    gender: {
        type: String,
        required: [true, 'Please provide gender'],
        enum: ['male', 'female', 'other'],
    },
    bloodGroup: {
        type: String,
        required: [true, 'Please provide blood group'],
        enum: ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
    },
    height: {
        type: Number,
        min: [100, 'Height must be at least 100cm'],
        max: [250, 'Height cannot exceed 250cm'],
    },
    weight: {
        type: Number,
        min: [40, 'Weight must be at least 40kg'],
        max: [200, 'Weight cannot exceed 200kg'],
    },
    address: {
        type: String,
        trim: true,
    },
    donationCount: {
        type: Number,
        default: 0,
        min: 0,
    },
    lastDonationDate: {
        type: Date,
    },
    healthInfo: {
        recentIllness: {
            type: String,
            enum: ['yes', 'no'],
            default: 'no',
        },
        substanceUse: {
            type: String,
            enum: ['yes', 'no'],
            default: 'no',
        },
        medicalProcedures: {
            type: String,
            enum: ['yes', 'no'],
            default: 'no',
        },
        chronicDiseases: {
            type: String,
            enum: ['yes', 'no'],
            default: 'no',
        },
        pregnancy: {
            type: String,
            enum: ['yes', 'no', 'N/A'],
            default: 'N/A',
        },
    },
    status: {
        type: String,
        enum: ['active', 'inactive'],
        default: 'active',
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
donorSchema.pre('save', function (next) {
    this.updatedAt = Date.now();
    next();
});

// Create indexes for faster queries
donorSchema.index({ email: 1 });
donorSchema.index({ bloodGroup: 1 });
donorSchema.index({ status: 1 });

export default mongoose.model('Donor', donorSchema);
