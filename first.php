<?php
// Database connection details
$server = "localhost";
$username = "root";
$password = "";
$database = "blood_bank"; // Ensure database exists

// Create a connection
$con = mysqli_connect($server, $username, $password, $database);

// Check if the connection was successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect post variables securely
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $dob = trim($_POST['dob']);
    $gender = trim($_POST['gender']);
    $blood_group = trim($_POST['blood_group']);

    // Check if required fields are empty
    if (empty($name) || empty($email) || empty($contact) || empty($dob) || empty($gender) || empty($blood_group)) {
        echo "All fields are required!";
    } else {
        // Use prepared statements to prevent SQL injection
        $stmt = $con->prepare("INSERT INTO donor (name, email, contact, dob, gender, blood_group) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $contact, $dob, $gender, $blood_group);

        // Execute and check for success
        if ($stmt->execute()) {
            echo "Donor information submitted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$con->close();
?>

     
  
     <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     
     <title>Hemo Hub - Donor</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
     <style>
         :root {
             --primary-color: #e53935;
             --primary-dark: #c62828;
             --secondary-color: #f5f5f5;
             --text-dark: #333333;
             --text-light: #ffffff;
         }
         
         body {
             font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
             color: var(--text-dark);
             background-color: #f9f9f9;
         }
         
         .navbar {
             background-color: var(--primary-color) !important;
             box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
             padding: 12px 20px;
         }
         
         .navbar-brand {
             font-weight: 700;
             font-size: 1.5rem;
             color: white !important;
         }
         
         .navbar-dark .navbar-nav .nav-link {
             color: rgba(255, 255, 255, 0.9) !important;
             font-weight: 500;
             padding: 8px 15px;
             border-radius: 4px;
             transition: all 0.3s;
         }
         
         .navbar-dark .navbar-nav .nav-link:hover {
             background-color: rgba(255, 255, 255, 0.1);
             transform: translateY(-2px);
         }
         
         .page-header {
             background: linear-gradient(135deg, #e53935 0%, #e35d5b 100%);
             color: white;
             padding: 40px 0;
             border-radius: 0 0 20px 20px;
             margin-bottom: 40px;
             text-align: center;
         }
         
         .page-header h1 {
             font-size: 2.5rem;
             font-weight: 700;
             margin-bottom: 15px;
         }
         
         .page-header p {
             font-size: 1.1rem;
             opacity: 0.9;
             max-width: 700px;
             margin: 0 auto;
         }
         
         .section-title {
             text-align: center;
             margin-bottom: 30px;
         }
         
         .section-title h3 {
             position: relative;
             display: inline-block;
             margin-bottom: 10px;
             font-weight: 700;
             color: var(--primary-color);
         }
         
         .section-title h3:after {
             content: '';
             display: block;
             width: 70%;
             height: 3px;
             background-color: var(--primary-color);
             margin: 8px auto 0;
         }
         
         .info-card {
             background-color: white;
             border-radius: 10px;
             box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
             padding: 25px;
             margin-bottom: 30px;
             transition: all 0.3s;
         }
         
         .info-card:hover {
             transform: translateY(-5px);
             box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
         }
         
         .donation-table {
             width: 100%;
             border-collapse: collapse;
             border-radius: 8px;
             overflow: hidden;
             box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
         }
         
         .donation-table th {
             background-color: var(--primary-color);
             color: white;
             padding: 15px;
             text-align: left;
             font-weight: 600;
         }
         
         .donation-table td {
             padding: 12px 15px;
             border-bottom: 1px solid #eee;
         }
         
         .donation-table tr:nth-child(even) {
             background-color: #f9f9f9;
         }
         
         .donation-table tr:hover {
             background-color: #f1f1f1;
         }
         
         .form-container {
             background-color: white;
             border-radius: 10px;
             box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
             padding: 30px;
             margin-bottom: 40px;
         }
         
         .form-group label {
             font-weight: 600;
             color: var(--text-dark);
             margin-bottom: 8px;
         }
         
         .form-control {
             border-radius: 5px;
             border: 1px solid #ddd;
             padding: 5px 5px;
         }
         
         .form-control:focus {
             border-color: var(--primary-color);
             box-shadow: 0 0 0 0.2rem rgba(229, 57, 53, 0.25);
         }
         
         .btn-primary {
             background-color: var(--primary-color);
             border-color: var(--primary-color);
             padding: 10px 20px;
             font-weight: 600;
             border-radius: 5px;
             transition: all 0.3s;
         }
         
         .btn-primary:hover {
             background-color: var(--primary-dark);
             border-color: var(--primary-dark);
             transform: translateY(-2px);
             box-shadow: 0 5px 15px rgba(229, 57, 53, 0.3);
         }
         
         footer {
             background-color: #333;
             color: white;
             padding: 40px 0 0;
             margin-top: 60px;
         }
         
         .footer-content {
             display: flex;
             flex-wrap: wrap;
             justify-content: space-between;
             max-width: 1200px;
             margin: 0 auto;
             padding: 0 20px;
         }
         
         .footer-section {
             flex: 1;
             min-width: 250px;
             margin-bottom: 30px;
         }
         
         .footer-section h2 {
             font-size: 1.5rem;
             margin-bottom: 20px;
             color: var(--primary-color);
         }
         
         .footer-section ul {
             list-style: none;
             padding: 0;
         }
         
         .footer-section ul li {
             margin-bottom: 10px;
         }
         
         .footer-section a {
             color: #ddd;
             text-decoration: none;
             transition: color 0.3s;
         }
         
         .footer-section a:hover {
             color: var(--primary-color);
         }
         
         .copywrites {
             background-color: #222;
             text-align: center;
             padding: 15px 0;
             margin-top: 20px;
         }
         
         @media only screen and (max-width: 768px) {
             .donation-table {
                 display: block;
                 overflow-x: auto;
             }
         }
     </style>
     <script>
         function validateForm() {
             var name = document.forms["donorForm"]["name"].value;
             var email = document.forms["donorForm"]["email"].value;
             var contact = document.forms["donorForm"]["contact"].value;
             var dob = document.forms["donorForm"]["dob"].value;
             var gender = document.forms["donorForm"]["gender"].value;
             var blood_group = document.forms["donorForm"]["blood_group"].value;
 
             if (name == "" || email == "" || contact == "" || dob == "" || gender == "" || blood_group == "") {
                 alert("All fields must be filled out");
                 return false;
             }
             alert("Your Application Has Been Submitted");
             return true;
         }
     </script>
 </head>
 <body>
     
     <!-- Navigation Bar -->
     <nav class="navbar navbar-expand-md navbar-dark">
         <div class="container">
             <a class="navbar-brand" href="Index.html">
                 <i class="fas fa-heartbeat mr-2"></i>Hemo Hub
             </a>
             <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                 <span class="navbar-toggler-icon"></span>
             </button>
             <div class="collapse navbar-collapse" id="collapsibleNavbar">
                 <ul class="navbar-nav ml-auto">
                     <li class="nav-item">
                         <a class="nav-link" href="Index.html"><i class="fas fa-home mr-1"></i> Home</a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link active" href="Donor.html"><i class="fas fa-hand-holding-heart mr-1"></i> Donor</a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link" href="BloodAvailability.html"><i class="fas fa-tint mr-1"></i> Blood Availability</a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link" href="Receiver.html"><i class="fas fa-user mr-1"></i> Receiver</a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link" href="About.html"><i class="fas fa-info-circle mr-1"></i> About</a>
                     </li>
                     
                     
                 </ul>
             </div>
         </div>
     </nav>
 
     <!-- Header Section -->
     <section class="page-header">
         <div class="container">
             <h1><i class="fas fa-hand-holding-heart mr-2"></i>Become a Blood Donor</h1>
             <p>Your donation can save up to three lives. Join our community of donors and make a difference today.</p>
         </div>
     </section>
 
     <!-- Eligibility Section -->
     <div class="container">
         <div class="section-title">
             <h3><i class="fas fa-clipboard-check mr-2"></i>Donation Eligibility</h3>
             <p class="text-muted">Check if you're eligible to donate blood</p>
         </div>
         
         <div class="info-card">
             <div class="table-responsive">
                 <table class="donation-table">
                     <thead>
                         <tr>
                             <th><i class="fas fa-check-circle mr-1"></i> You CAN Donate If</th>
                             <th><i class="fas fa-times-circle mr-1"></i> You CAN'T Donate If</th>
                         </tr>
                     </thead>
                     <tbody>
                         <tr>
                             <td>1. You are fit and healthy</td>
                             <td>1. You have a cold, sore throat or flu</td>
                         </tr>
                         <tr>
                             <td>2. You are between ages 18-70</td>
                             <td>2. You have a chronic infectious disease</td>
                         </tr>
                         <tr>
                             <td>3. You weigh more than 45kg</td>
                             <td>3. You are taking antibiotics</td>
                         </tr>
                         <tr>
                             <td>4. You have had no operation in the last six months</td>
                             <td>4. You have had recent surgery</td>
                         </tr>
                         <tr>
                             <td>5. You may have high cholesterol</td>
                             <td>5. You are pregnant or breastfeeding</td>
                         </tr>
                         <tr>
                             <td>6. You take blood pressure medication (if pressure is stable)</td>
                             <td>6. You have had an extended stay in certain countries</td>
                         </tr>
                     </tbody>
                 </table>
             </div>
         </div>
     </div>
 
     <!-- Donor Form Section -->
     <div class="container">
         <div class="section-title">
             <h3><i class="fas fa-user-plus mr-2"></i>Donor Registration Form</h3>
             <p class="text-muted">Register as a blood donor and help save lives</p>
         </div>
       
         <div class="form-container">
             <form name="donorForm" onsubmit="return validateForm()"  method="POST" action="first.php" >  
                 <div class="row">
                     <div class="col-md-6">
                         <div class="form-group">
                             <label for="name"><i class="fas fa-user mr-1"></i> Full Name:</label>
                             <input type="text" class="form-control" id="name" placeholder="Enter your full name" name="name" required>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="form-group">
                             <label for="email"><i class="fas fa-envelope mr-1"></i> Email:</label>
                             <input type="email" class="form-control" id="email" placeholder="Enter your email" name="email" required>
                         </div>
                     </div>
                 </div>
                 
                 <div class="row">
                     <div class="col-md-6">
                         <div class="form-group">
                             <label for="contact"><i class="fas fa-phone mr-1"></i> Contact Number:</label>
                             <input type="text" class="form-control" id="contact" placeholder="Enter your contact number" name="contact" required>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="form-group">
                             <label for="dob"><i class="fas fa-calendar-alt mr-1"></i> Date of Birth:</label>
                             <input type="date" class="form-control" id="dob" name="dob" required>
                         </div>
                     </div>
                 </div>
                 
                 <div class="row">
                     <div class="col-md-6">
                         <div class="form-group">
                             <label for="gender"><i class="fas fa-venus-mars mr-1"></i> Gender:</label>
                             <select class="form-control" id="gender" name="gender" required>
                                 <option value="">-- Select Gender --</option>
                                 <option value="male">Male</option>
                                 <option value="female">Female</option>
                                 <option value="other">Other</option>
                             </select>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="form-group">
                             <label for="blood_group"><i class="fas fa-tint mr-1"></i> Blood Group:</label>
                             <select class="form-control" id="blood_group" name="blood_group" required>
                                 <option value="">-- Select Blood Group --</option>
                                 <option value="a+">A+</option>
                                 <option value="a-">A-</option>
                                 <option value="b+">B+</option>
                                 <option value="b-">B-</option>
                                 <option value="o+">O+</option>
                                 <option value="o-">O-</option>
                                 <option value="ab+">AB+</option>
                                 <option value="ab-">AB-</option>
                             </select>
                         </div>
                     </div>
                 </div>
                 
                 <div class="form-group form-check">
                     <input type="checkbox" class="form-check-input" id="agreement" required>
                     <label class="form-check-label" for="agreement">I confirm that I have read the eligibility criteria and I am eligible to donate blood.</label>
                 </div>
                 
                 <button type="submit" class="btn btn-primary btn-lg btn-block">
                     <i class="fas fa-paper-plane mr-1"></i> Submit Application
                 </button>
             </form>
         </div>
     </div>
 
     <!-- Footer -->
     <footer>
         <div class="footer-content">
             <div class="footer-section">
                 <h2>About Us</h2>
                 <p>Hemo Hub is dedicated to connecting blood donors with those in need, ensuring timely access to safe and life-saving blood</p>
                 <a href="About.html" class="btn btn-outline-light btn-sm mt-2">Learn More</a>
             </div>
             <div class="footer-section">
                 <h2>Quick Links</h2>
                 <ul>
                     <li><a href="Donor.html">Become a Donor</a></li>
                     <li><a href="BloodAvailability.html">Find Blood</a></li>
                     <li><a href="Receiver.html">Request Blood</a></li>
                     <li><a href="LifeSavers.html">Our Life Savers</a></li>
                 </ul>
             </div>
             <div class="footer-section">
                 <h2>Contact Us</h2>
                 <p><i class="fas fa-map-marker-alt mr-2"></i>VIT,Bibwewadi,Pune,Maharashtra 411037</p>
                 <p><i class="fas fa-envelope mr-2"></i>hemohub@vit.edu</p>
                 <p><i class="fas fa-phone mr-2"></i>+91 87676 48894</p>
             </div>
             <div class="footer-section">
                 <h2>Connect With Us</h2>
                 <p>Follow us on social media for updates on blood donation camps and success stories.</p>
                 <div class="social-icons">
                     <a href="#"><i class="fab fa-facebook"></i></a>
                     <a href="#"><i class="fab fa-twitter"></i></a>
                     <a href="#"><i class="fab fa-instagram"></i></a>
                     <a href="#"><i class="fab fa-linkedin"></i></a>
                 </div>
             </div>
         </div>
         <div class="copywrites">
             <p>© 2025 Hemo Hub. Connecting Donors, Saving Lives.</p>
         </div>
     </footer>
 
     <!-- Scripts -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>
 </body>
 
 </html>
 