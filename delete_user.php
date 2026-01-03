<!-- delete_user.php -->
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

// Delete user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = mysqli_real_escape_string($con, $_POST['userId']);
    $userType = mysqli_real_escape_string($con, $_POST['userType']);

    if ($userType == 'donor') {
        $query = "DELETE FROM donor WHERE id = '$userId'";
    } else {
        $query = "DELETE FROM receiver WHERE id = '$userId'";
    }

    if (mysqli_query($con, $query)) {
        echo "User deleted successfully!";
    } else {
        echo "Error deleting user: " . mysqli_error($con);
    }
}

mysqli_close($con);
?>