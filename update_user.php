<?php
$host = 'localhost';     // XAMPP default is localhost
$user = 'root';          // Default user in XAMPP
$pass = '';               // Default password is empty
$db_name = 'blood_bank';  // Your actual database name

$conn = mysqli_connect($host, $user, $pass, $db_name);

if (!$conn) {
    die('Connection Failed: ' . mysqli_connect_error());
}


$userId = $_POST['userId'];
$userType = $_POST['userType'];

$name = $_POST['name'];
$email = $_POST['email'];
$contact = $_POST['contact'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$bloodGroup = $_POST['bloodGroup'];

if ($userType === 'donor') {
    $sql = "UPDATE donors SET 
            name = ?, email = ?, contact = ?, dob = ?, gender = ?, blood_group = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $name, $email, $contact, $dob, $gender, $bloodGroup, $userId);
} else {
    $urgency = $_POST['urgency'];
    $hospital = $_POST['hospital'];
    $additionalInfo = $_POST['additionalInfo'];

    $sql = "UPDATE receivers SET 
            name = ?, email = ?, contact = ?, dob = ?, gender = ?, blood_group = ?, 
            urgency = ?, hospital = ?, additional_info = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssi", $name, $email, $contact, $dob, $gender, $bloodGroup, $urgency, $hospital, $additional_Info,$userId);
}

if ($stmt->execute()) {
    echo "User updated successfully!";
} else {
    echo "Failed to update user.";
}

$stmt->close();
$conn->close();


?>
