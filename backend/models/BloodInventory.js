import mongoose from 'mongoose';

const bloodInventorySchema = new mongoose.Schema({
    bloodType: {
        type: String,
        required: [true, 'Please provide blood type'],
        unique: true,
        enum: ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
    },
    unitsAvailable: {
        type: Number,
        required: [true, 'Please provide units available'],
        min: [0, 'Units cannot be negative'],
        default: 0,
    },
    status: {
        type: String,
        enum: ['Available', 'Limited', 'Critical'],
        default: 'Available',
    },
    lastUpdated: {
        type: Date,
        default: Date.now,
    },
});

// Automatically update status based on units available
bloodInventorySchema.pre('save', function (next) {
    this.lastUpdated = Date.now();

    if (this.unitsAvailable >= 20) {
        this.status = 'Available';
    } else if (this.unitsAvailable >= 10) {
        this.status = 'Limited';
    } else {
        this.status = 'Critical';
    }

    next();
});

// Static method to update inventory
bloodInventorySchema.statics.updateInventory = async function (bloodType, unitsChange) {
    const inventory = await this.findOne({ bloodType });

    if (!inventory) {
        throw new Error('Blood type not found in inventory');
    }

    inventory.unitsAvailable += unitsChange;

    if (inventory.unitsAvailable < 0) {
        throw new Error('Insufficient units in inventory');
    }

    await inventory.save();
    return inventory;
};

export default mongoose.model('BloodInventory', bloodInventorySchema);
