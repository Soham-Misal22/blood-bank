import dotenv from 'dotenv';
import mongoose from 'mongoose';
import Admin from '../models/Admin.js';

// Load env variables
dotenv.config();

// Connect to database
mongoose.connect(process.env.MONGODB_URI);

const createAdmin = async () => {
    try {
        // Check if admin already exists
        const existingAdmin = await Admin.findOne({ email: 'admin@hemohub.com' });

        if (existingAdmin) {
            console.log('⚠️  Admin already exists');
            process.exit(0);
        }

        // Create new admin
        const admin = await Admin.create({
            name: 'Admin',
            email: 'admin@hemohub.com',
            password: 'admin123',
            role: 'superadmin',
        });

        console.log('✅ Admin created successfully');
        console.log(`📧 Email: ${admin.email}`);
        console.log(`🔑 Password: admin123`);
        console.log(`⚡ Role: ${admin.role}`);
        console.log('\n⚠️  Please change the password after first login!');

        process.exit(0);
    } catch (error) {
        console.error('❌ Error creating admin:', error);
        process.exit(1);
    }
};

createAdmin();
