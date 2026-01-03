<!-- get_user_details.php -->

<?php
// Database connection
$server = "localhost";
$username = "root";
$password = "";
$database = "blood_bank";

$con = mysqli_connect($server, $username, $password, $database);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get user details
if (isset($_POST['userId']) && isset($_POST['userType'])) {
    $userId = mysqli_real_escape_string($con, $_POST['userId']);
    $userType = mysqli_real_escape_string($con, $_POST['userType']);

    if ($userType == 'donor') {
        $query = "SELECT id, name, email, contact, dob, gender, blood_group, last_donation_date, address FROM donor WHERE id = '$userId'";
    } else {
        $query = "SELECT id, name, email, blood_group, contact, address, medical_condition, urgency_level FROM receiver WHERE id = '$userId'";
    }

    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About Us - Hemo Hub</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
   <style>
    /* Previous CSS styles remain the same */
    .management-section {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 40px;
    }

    .table-management {
        width: 100%;
        margin-bottom: 20px;
    }

    .edit-form {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
    }
   </style>
</head>
<body>
   <!-- Navigation Bar (previous code remains the same) -->
   
   <!-- Page Header (previous code remains the same) -->

   <!-- Existing About Content (previous code remains the same) -->

   <!-- Database Management Section -->
   <div class="container">
    <div class="management-section">
        <h3 class="section-title"><i class="fas fa-database mr-2"></i>Database Management</h3>

        <div class="btn-group mb-3">
            <button class="btn btn-primary" id="showDonorsBtn">Show Donors</button>
            <button class="btn btn-secondary" id="showReceiversBtn">Show Receivers</button>
        </div>

        <div id="donorSection">
            <h4>Donor Management</h4>
            <table class="table table-striped table-management">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>DOB</th>
                        <th>Gender</th>
                        <th>Blood Group</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="donorTableBody">
                    <!-- Donor records will be dynamically populated -->
                </tbody>
            </table>
        </div>

        <div id="receiverSection" style="display:none;">
            <h4>Receiver Management</h4>
            <table class="table table-striped table-management">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>DOB</th>
                        <th>Gender</th>
                        <th>Blood Group</th>
                        <th>Urgency</th>
                        <th>Hospital</th>
                        <th>Additional Info</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="receiverTableBody">
                    <!-- Receiver records will be dynamically populated -->
                </tbody>
            </table>
        </div>

        <!-- Edit User Form (remains the same as your provided code) -->
        <div class="edit-form">
            <h4>Edit User Details</h4>
            <form id="editUserForm">
                <input type="hidden" id="editUserId" name="userId">
                <input type="hidden" id="editUserType" name="userType">

                <div class="form-group">
                    <label for="editName">Full Name</label>
                    <input type="text" class="form-control" id="editName" name="name" required>
                </div>

                <div class="form-group">
                    <label for="editEmail">Email</label>
                    <input type="email" class="form-control" id="editEmail" name="email" required>
                </div>

                <div class="form-group">
                    <label for="editContact">Contact</label>
                    <input type="text" class="form-control" id="editContact" name="contact" required>
                </div>

                <div class="form-group">
                    <label for="editDOB">Date of Birth</label>
                    <input type="date" class="form-control" id="editDOB" name="dob" required>
                </div>

                <div class="form-group">
                    <label for="editGender">Gender</label>
                    <select class="form-control" id="editGender" name="gender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="editBloodGroup">Blood Group</label>
                    <select class="form-control" id="editBloodGroup" name="bloodGroup">
                        <option>A+</option><option>A-</option>
                        <option>B+</option><option>B-</option>
                        <option>O+</option><option>O-</option>
                        <option>AB+</option><option>AB-</option>
                    </select>
                </div>

                <!-- Extra fields only for receivers -->
                <div id="receiverFields" style="display: none;">
                    <div class="form-group">
                        <label for="editUrgency">Urgency</label>
                        <input type="text" class="form-control" id="editUrgency" name="urgency">
                    </div>

                    <div class="form-group">
                        <label for="editHospital">Hospital</label>
                        <input type="text" class="form-control" id="editHospital" name="hospital">
                    </div>

                    <div class="form-group">
                        <label for="editAdditionalInfo">Additional Info</label>
                        <textarea class="form-control" id="editAdditionalInfo" name="additionalInfo"></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update User</button>
            </form>
        </div>

    </div>
</div>



   <!-- Footer (previous code remains the same) -->

   <!-- Scripts -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.min.js"></script>
   <script>
   


   $(document).ready(function() {
    // Load both tables initially (optional if you only want to load on view switch)
    loadDonors();
    loadReceivers();

    // Show Donors and Hide Receivers initially
    $('#donorSection').show();
    $('#receiverSection').hide();

    // Button Event Listeners
    $('#showDonorsBtn').click(function() {
        $('#donorSection').show();
        $('#receiverSection').hide();
    });

    $('#showReceiversBtn').click(function() {
        $('#receiverSection').show();
        $('#donorSection').hide();
    });

    // Function to load donors
    function loadDonors() {
        $.get('load_donors.php', function(response) {
            $('#donorTableBody').html(response);
        });
    }

    // Function to load receivers
    function loadReceivers() {
        $.get('load_receivers.php', function(response) {
            $('#receiverTableBody').html(response);
        });
    }

    // Edit User Logic (with userType support)
    $(document).on('click', '.edit-user', function() {
    var userId = $(this).data('id');
    var userType = $(this).data('type'); // 'donor' or 'receiver'

    $.ajax({
        url: 'get_user_details.php',
        method: 'POST',
        data: { userId: userId, userType: userType },
        success: function(response) {
            var user = JSON.parse(response);

            $('#editUserId').val(user.id);
            $('#editUserType').val(userType);

            $('#editName').val(user.name);
            $('#editEmail').val(user.email);
            $('#editContact').val(user.contact);
            $('#editDOB').val(user.dob);
            $('#editGender').val(user.gender);
            $('#editBloodGroup').val(user.blood_group);

            if (userType === 'receiver') {
                $('#receiverFields').show();
                $('#editUrgency').val(user.urgency);
                $('#editHospital').val(user.hospital);
                $('#editAdditionalInfo').val(user.additional_info);
            } else {
                $('#receiverFields').hide();
            }

            // Scroll to the form
            $('html, body').animate({
                scrollTop: $('.edit-form').offset().top - 50
            }, 500);
        }
    });
});


    // Form Submit (update_user.php handles both donor/receiver)
    $('#editUserForm').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'update_user.php',
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            alert(response); // Show success/fail message
            loadDonors();
            loadReceivers();
        }
    });
});


    // Delete User
    $(document).on('click', '.delete-user', function() {
        var userId = $(this).data('id');
        var userType = $(this).data('type');

        if (confirm('Are you sure you want to delete this user?')) {
            $.post('delete_user.php', { userId, userType }, function(response) {
                alert(response);
                loadDonors();
                loadReceivers();
            });
        }
    });
});

   </script>
</body>
</html>