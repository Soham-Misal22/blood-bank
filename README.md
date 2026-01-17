# 🩸 Blood Bank Management System

A comprehensive blood bank management system built with **Node.js**, **Express**, **MongoDB**, and **Mongoose**. Features donor registration, blood inventory tracking, receiver requests, and an admin dashboard with JWT authentication.

## ✨ Features

- 🩸 **Donor Management**: Register and manage blood donors
- 📦 **Blood Inventory**: Real-time blood availability tracking
- 🏥 **Receiver Requests**: Submit and manage blood requests
- 🔐 **Admin Authentication**: Secure login with JWT and httpOnly cookies
- 🛡️ **Security**: Bcrypt password hashing, input validation, CORS protection
- 📊 **RESTful API**: Clean and well-documented API endpoints

## 🚀 Getting Started

### Prerequisites

- Node.js (v16 or higher)
- MongoDB (running locally or MongoDB Atlas)
- Git

### Installation

1. **Clone the repository**
   ```bash
   cd Blood-Bank-project-main11
   ```

2. **Install backend dependencies**
   ```bash
   cd backend
   npm install
   ```

3. **Set up environment variables**
   
   Copy `.env.example` to `.env` and update the values:
   ```bash
   cp .env.example .env
   ```

   Edit `.env` file:
   ```env
   PORT=5000
   MONGODB_URI=mongodb://localhost:27017/blood_bank
   NODE_ENV=development
   JWT_SECRET=your-super-secret-jwt-key-change-this
   JWT_EXPIRE=7d
   JWT_COOKIE_EXPIRE=7
   CLIENT_URL=http://127.0.0.1:5500
   ```

4. **Start MongoDB**
   ```bash
   # If MongoDB is installed locally
   mongod
   ```

5. **Seed the database**
   ```bash
   # Seed blood inventory
   npm run seed

   # Create default admin (optional)
   node scripts/createAdmin.js
   ```

   Default admin credentials:
   - Email: `admin@hemohub.com`
   - Password: `admin123`

6. **Start the backend server**
   ```bash
   npm run dev
   ```

   Server will run on `http://localhost:5000`

7. **Launch the frontend**
   
   Open any HTML file with Live Server or simply open in browser:
   - Navigate to the project root
   - Open `_Index.html` in your browser

## 📁 Project Structure

```
Blood-Bank-project-main11/
├── backend/
│   ├── config/
│   │   └── database.js          # MongoDB connection
│   ├── models/
│   │   ├── Admin.js             # Admin model with bcrypt
│   │   ├── Donor.js             # Donor model
│   │   ├── Receiver.js          # Receiver model
│   │   └── BloodInventory.js    # Blood inventory model
│   ├── controllers/
│   │   ├── authController.js    # Authentication logic
│   │   ├── donorController.js   # Donor CRUD
│   │   ├── receiverController.js
│   │   └── inventoryController.js
│   ├── routes/
│   │   ├── auth.js              # Auth routes
│   │   ├── donors.js            # Donor routes
│   │   ├── receivers.js         # Receiver routes
│   │   └── inventory.js         # Inventory routes
│   ├── middleware/
│   │   ├── auth.js              # JWT verification
│   │   ├── validation.js        # Input validation
│   │   └── errorHandler.js      # Error handling
│   ├── scripts/
│   │   ├── seedBloodInventory.js
│   │   └── createAdmin.js
│   ├── .env                     # Environment variables
│   ├── package.json
│   └── server.js                # Express server
├── frontend/ (HTML files in root)
│   ├── _Index.html
│   ├── Donor.html
│   ├── Receiver.html
│   ├── BloodAvailability.html
│   └── ...
└── README.md
```

## 🔌 API Endpoints

### Authentication
```
POST   /api/auth/register     - Register new admin
POST   /api/auth/login        - Admin login
GET    /api/auth/me           - Get current admin (protected)
GET    /api/auth/logout       - Logout admin (protected)
```

### Donors
```
GET    /api/donors                    - Get all donors
GET    /api/donors/:id                - Get donor by ID
POST   /api/donors                    - Create new donor
PUT    /api/donors/:id                - Update donor (protected)
DELETE /api/donors/:id                - Delete donor (protected)
GET    /api/donors/bloodgroup/:type   - Get donors by blood group
```

### Receivers
```
GET    /api/receivers                 - Get all receivers
GET    /api/receivers/:id             - Get receiver by ID
POST   /api/receivers                 - Create blood request
PUT    /api/receivers/:id             - Update request (protected)
DELETE /api/receivers/:id             - Delete request (protected)
GET    /api/receivers/urgency/:level  - Get by urgency (protected)
```

### Blood Inventory
```
GET    /api/inventory                 - Get all blood inventory
GET    /api/inventory/:bloodType      - Get specific blood type
PUT    /api/inventory/:bloodType      - Update inventory (protected)
POST   /api/inventory/:bloodType/add  - Add units (protected)
POST   /api/inventory/:bloodType/remove - Remove units (protected)
GET    /api/inventory/status/critical - Get critical stock
```

## 🔐 Authentication

The system uses JWT tokens stored in httpOnly cookies for secure authentication:

1. **Login**: Send credentials to `/api/auth/login`
2. **Token**: Receive JWT in httpOnly cookie (automatically sent with requests)
3. **Protected Routes**: Include cookie in requests to protected endpoints
4. **Logout**: Clear cookie via `/api/auth/logout`

## 🛠️ Technologies Used

### Backend
- **Node.js** - JavaScript runtime
- **Express** - Web framework
- **MongoDB** - NoSQL database
- **Mongoose** - MongoDB ODM
- **bcryptjs** - Password hashing
- **jsonwebtoken** - JWT authentication
- **cookie-parser** - Cookie handling
- **express-validator** - Input validation
- **helmet** - Security headers
- **cors** - Cross-origin resource sharing

### Frontend
- **HTML5** - Structure
- **CSS3** - Styling with custom design
- **JavaScript** - Client-side logic
- **Bootstrap 4.6** - UI framework
- **Font Awesome** - Icons

## 📊 Database Schema

### Donor
- name, email, contact, dob, gender, bloodGroup
- height, weight, address, donationCount
- lastDonationDate, healthInfo, status

### Receiver
- name, email, contact, bloodGroup, hospital
- medicalCondition, urgencyLevel, unitsNeeded
- requestDate, status, notes

### BloodInventory
- bloodType (A+, A-, B+, B-, O+, O-, AB+, AB-)
- unitsAvailable, status (Available/Limited/Critical)
- lastUpdated

### Admin
- name, email, password (hashed)
- role (admin/superadmin), createdAt

## 🧪 Testing the API

### Using Postman or Thunder Client

1. **Register Admin**
   ```json
   POST http://localhost:5000/api/auth/register
   {
     "name": "Admin Name",
     "email": "admin@example.com",
     "password": "password123"
   }
   ```

2. **Login**
   ```json
   POST http://localhost:5000/api/auth/login
   {
     "email": "admin@hemohub.com",
     "password": "admin123"
   }
   ```

3. **Get Blood Inventory**
   ```
   GET http://localhost:5000/api/inventory
   ```

## 🚨 Security Features

- ✅ Password hashing with bcrypt
- ✅ JWT authentication with httpOnly cookies
- ✅ Input validation and sanitization
- ✅ CORS protection
- ✅ Security headers with Helmet
- ✅ MongoDB injection prevention
- ✅ Role-based access control

## 📝 Scripts

```bash
npm start          # Start production server
npm run dev        # Start development server with nodemon
npm run seed       # Seed blood inventory database
```

## 🐛 Troubleshooting

### MongoDB Connection Error
- Make sure MongoDB is running: `mongod`
- Check connection string in `.env`
- For Windows: MongoDB might be running as a service already

### Port Already in Use
- Change PORT in `.env` file
- Or kill the process using port 5000

### CORS Errors
- Update CLIENT_URL in `.env` to match your frontend URL
- Make sure credentials: true is set in axios/fetch requests

## 📄 License

This project is licensed under the ISC License.

## 👥 Contributors

Developed for VIT, Bibwewadi, Pune, Maharashtra

---

**For any issues or questions, please check the troubleshooting section or create an issue in the repository.**
