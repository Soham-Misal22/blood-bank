import dotenv from 'dotenv';
import mongoose from 'mongoose';
import BloodInventory from '../models/BloodInventory.js';

// Load env variables
dotenv.config();

// Connect to database
mongoose.connect(process.env.MONGODB_URI);

const bloodTypes = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];

const seedData = bloodTypes.map((type) => ({
    bloodType: type,
    unitsAvailable: Math.floor(Math.random() * 30) + 10, // Random units between 10-40
}));

const seedInventory = async () => {
    try {
        // Clear existing data
        await BloodInventory.deleteMany();
        console.log('🗑️  Cleared existing inventory data');

        // Insert new data
        await BloodInventory.insertMany(seedData);
        console.log('✅ Blood inventory seeded successfully');

        // Display seeded data
        const inventory = await BloodInventory.find().sort({ bloodType: 1 });
        console.log('\n📊 Current Inventory:');
        inventory.forEach((item) => {
            console.log(
                `${item.bloodType}: ${item.unitsAvailable} units (${item.status})`
            );
        });

        process.exit(0);
    } catch (error) {
        console.error('❌ Error seeding inventory:', error);
        process.exit(1);
    }
};

seedInventory();
