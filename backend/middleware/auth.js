import jwt from 'jsonwebtoken';
import Admin from '../models/Admin.js';

// Protect routes - verify JWT token
export const protect = async (req, res, next) => {
    let token;

    // Check for token in cookies
    if (req.cookies.token) {
        token = req.cookies.token;
    }

    // Make sure token exists
    if (!token) {
        return res.status(401).json({
            success: false,
            message: 'Not authorized to access this route',
        });
    }

    try {
        // Verify token
        const decoded = jwt.verify(token, process.env.JWT_SECRET);

        // Get admin from token
        req.admin = await Admin.findById(decoded.id);

        if (!req.admin) {
            return res.status(401).json({
                success: false,
                message: 'Admin not found',
            });
        }

        next();
    } catch (error) {
        return res.status(401).json({
            success: false,
            message: 'Not authorized to access this route',
        });
    }
};

// Grant access to specific roles
export const authorize = (...roles) => {
    return (req, res, next) => {
        if (!roles.includes(req.admin.role)) {
            return res.status(403).json({
                success: false,
                message: `User role ${req.admin.role} is not authorized to access this route`,
            });
        }
        next();
    };
};
