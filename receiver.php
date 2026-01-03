<?php
// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$server = "localhost";
$username = "root";
$password = "";
$database = "blood_bank"; 

$con = mysqli_connect($server, $username, $password, $database);

// Check connection
if (!$con) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $dob = trim($_POST['dob']);
    $gender = trim($_POST['gender']);
    $blood_group = trim($_POST['blood_group']);
    $urgency = trim($_POST['urgency']);
    $hospital = trim($_POST['hospital']);
    $additional_info = trim($_POST['additional_info']);

    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("INSERT INTO receiver (name, email, contact, dob, gender, blood_group, urgency, hospital, additional_info) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        die("Prepare failed: " . $con->error);
    }

    $stmt->bind_param("sssssssss", $name, $email, $contact, $dob, $gender, $blood_group, $urgency, $hospital, $additional_info);

    // Execute and check for success
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();

    // Redirect after successful insertion
    header("Location: Receiver.html");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Receiver - Hemo Hub</title>
    <link rel="HTML" href="Donor.html">
    <link rel="HTML" href="Receiver.html">
    <link rel="HTML" href="BloodAvailability.html">
    <link rel="HTML" href="LifeSavers.html">
    <link rel="HTML" href="About.html">
    <link rel="HTML" href="Index.html">
    <link rel="HTML" href="Botboy.html">
    <link rel="stylesheet" href="Styles.css">
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
        
        .content-section {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 40px;
        }
        
        .section-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }
        
        .section-title:after {
            content: '';
            display: block;
            width: 70%;
            height: 3px;
            background-color: var(--primary-color);
            margin: 8px 0 0;
        }
        
        .blood-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .blood-table th {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 12px;
            text-align: center;
            font-weight: 600;
        }
        
        .blood-table td {
            border: 1px solid #f5c6cb;
            padding: 12px;
            text-align: center;
        }
        
        .blood-table tr:nth-child(even) {
            background-color: #fff9f9;
        }
        
        .blood-table tr:hover {
            background-color: #f8d7da;
        }
        
        form {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            display: block;
        }
        
        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 5px 5px;
            transition: all 0.3s;
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
            margin-top: 10px;
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
        
        @media (max-width: 768px) {
            .blood-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
    <script>
        function validateForm() {
            var name = document.forms["receiverForm"]["name"].value;
            var email = document.forms["receiverForm"]["email"].value;
            var contact = document.forms["receiverForm"]["contact"].value;
            var dob = document.forms["receiverForm"]["dob"].value;
            var gender = document.forms["receiverForm"]["gender"].value;
            var blood_group = document.forms["receiverForm"]["blood_group"].value;

            if (name == "" || email == "" || contact == "" || dob == "" || gender == "" || blood_group == "") {
                alert("All fields must be filled out");
                return false;
            }
            
            alert("Your blood request has been submitted successfully. Our team will contact you shortly.");
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
                        <a class="nav-link" href="Donor.html"><i class="fas fa-hand-holding-heart mr-1"></i> Donor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="BloodAvailability.html"><i class="fas fa-tint mr-1"></i> Blood Availability</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="Receiver.html"><i class="fas fa-user mr-1"></i> Receiver</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="About.html"><i class="fas fa-info-circle mr-1"></i> About</a>
                    </li>
                    
                    
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header text-center">
        <div class="container">
            <h1><i class="fas fa-user mr-2"></i>Blood Receiver</h1>
            <p>Request blood for those in need. Our system helps connect patients with the right blood donors quickly and efficiently.</p>
        </div>
    </section>

    <!-- Blood Compatibility Table -->
    <div class="container">
        <div class="content-section">
            <h2 class="section-title"><i class="fas fa-exchange-alt mr-2"></i>Blood Compatibility Chart</h2>
            <p class="mb-4">Understanding blood type compatibility is crucial for safe transfusions. Check the table below to see which blood types are compatible with yours.</p>
            
            <div class="table-responsive">
                <table class="blood-table">
                    <thead>
                        <tr>
                            <th>Blood Type</th>
                            <th>Can Donate To</th>
                            <th>Can Receive From</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="badge badge-danger">A+</span></td>
                            <td>A+, AB+</td>
                            <td>A+, A-, O+, O-</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-danger">O+</span></td>
                            <td>O+, A+, B+, AB+</td>
                            <td>O+, O-</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-danger">B+</span></td>
                            <td>B+, AB+</td>
                            <td>B+, B-, O+, O-</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-danger">AB+</span></td>
                            <td>AB+ only</td>
                            <td>Everyone (Universal Recipient)</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-danger">A-</span></td>
                            <td>A+, A-, AB+, AB-</td>
                            <td>A-, O-</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-danger">O-</span></td>
                            <td>Everyone (Universal Donor)</td>
                            <td>O- only</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-danger">B-</span></td>
                            <td>B+, B-, AB+, AB-</td>
                            <td>B-, O-</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-danger">AB-</span></td>
                            <td>AB+, AB-</td>
                            <td>AB-, A-, B-, O-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle mr-2"></i> O- is the universal donor (can donate to any blood type), while AB+ is the universal recipient (can receive from any blood type).
            </div>
        </div>
        
        <!-- Blood Receiver Form -->
        <div class="content-section">
            <h2 class="section-title"><i class="fas fa-clipboard-list mr-2"></i>Blood Receiver Form</h2>
            <p class="mb-4">If you or someone you know needs blood, please fill out this form. Our team will contact you as soon as possible to assist with the blood requirement.</p>
            
            <form name="receiverForm" onsubmit="return validateForm()" method="post" action="receiver.php">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name"><i class="fas fa-user mr-1"></i> Full Name:</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter full name" name="name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope mr-1"></i> Email Address:</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact"><i class="fas fa-phone mr-1"></i> Contact Number:</label>
                            <input type="text" class="form-control" id="contact" placeholder="Enter contact number" name="contact" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dob"><i class="fas fa-calendar mr-1"></i> Date of Birth:</label>
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
                            <label for="blood_group"><i class="fas fa-tint mr-1"></i> Blood Group Required:</label>
                            <select class="form-control" id="blood_group" name="blood_group" required>
                                <option value="">-- Select Blood Group --</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="urgency"><i class="fas fa-exclamation-circle mr-1"></i> Urgency Level:</label>
                    <select class="form-control" id="urgency" name="urgency" required>
                        <option value="">-- Select Urgency --</option>
                        <option value="high">High (Needed within 24 hours)</option>
                        <option value="medium">Medium (Needed within 3 days)</option>
                        <option value="low">Low (Needed within a week)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="hospital"><i class="fas fa-hospital mr-1"></i> Hospital/Medical Center:</label>
                    <input type="text" class="form-control" id="hospital" placeholder="Enter hospital or medical center name" name="hospital">
                </div>
                
                <div class="form-group">
                    <label for="additional_info"><i class="fas fa-info-circle mr-1"></i> Additional Information:</label>
                    <textarea class="form-control" id="additional_info" rows="3" placeholder="Any additional details about the requirement" name="additional_info"></textarea>
                </div>
                
                <div class="form-check mb-4">
                    <input type="checkbox" class="form-check-input" id="terms" required>
                    <label class="form-check-label" for="terms">I confirm that the information provided is accurate and I consent to be contacted regarding this request.</label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-paper-plane mr-1"></i> Submit Request
                </button>
            </form>
        </div>
        
        <!-- Emergency Contact -->
        <div class="content-section">
            <h2 class="section-title"><i class="fas fa-ambulance mr-2"></i>Emergency Contact</h2>
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading"><i class="fas fa-exclamation-triangle mr-2"></i>Need Blood Urgently?</h4>
                <p>For immediate blood requirements, please contact our 24/7 helpline:</p>
                <h3 class="mb-0"><i class="fas fa-phone-alt mr-2"></i>+91 87676 48894</h3>
            </div>
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