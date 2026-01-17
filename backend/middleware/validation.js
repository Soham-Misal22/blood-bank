import { body, validationResult } from 'express-validator';

// Validation middleware to check for errors
export const validate = (req, res, next) => {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
        return res.status(400).json({
            success: false,
            errors: errors.array(),
        });
    }
    next();
};

// Donor validation rules
export const donorValidation = [
    body('name').trim().notEmpty().withMessage('Name is required'),
    body('email').isEmail().withMessage('Please provide a valid email'),
    body('contact').trim().notEmpty().withMessage('Contact number is required'),
    body('dob').isDate().withMessage('Please provide a valid date of birth'),
    body('gender').isIn(['male', 'female', 'other']).withMessage('Invalid gender'),
    body('bloodGroup')
        .isIn(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'])
        .withMessage('Invalid blood group'),
    body('height').optional().isInt({ min: 100, max: 250 }).withMessage('Height must be between 100-250 cm'),
    body('weight').optional().isInt({ min: 40, max: 200 }).withMessage('Weight must be between 40-200 kg'),
];

// Receiver validation rules
export const receiverValidation = [
    body('name').trim().notEmpty().withMessage('Name is required'),
    body('email').isEmail().withMessage('Please provide a valid email'),
    body('contact').trim().notEmpty().withMessage('Contact number is required'),
    body('bloodGroup')
        .isIn(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'])
        .withMessage('Invalid blood group'),
    body('hospital').trim().notEmpty().withMessage('Hospital name is required'),
    body('urgencyLevel')
        .isIn(['critical', 'urgent', 'normal'])
        .withMessage('Invalid urgency level'),
    body('unitsNeeded').isInt({ min: 1, max: 10 }).withMessage('Units needed must be between 1-10'),
];

// Admin validation rules
export const adminValidation = [
    body('name').trim().notEmpty().withMessage('Name is required'),
    body('email').isEmail().withMessage('Please provide a valid email'),
    body('password').isLength({ min: 6 }).withMessage('Password must be at least 6 characters'),
];

// Login validation rules
export const loginValidation = [
    body('email').isEmail().withMessage('Please provide a valid email'),
    body('password').notEmpty().withMessage('Password is required'),
];
