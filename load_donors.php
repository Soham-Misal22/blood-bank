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

// Fetch donors
$query = "SELECT id, name, email, contact, dob, gender, blood_group FROM donor";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($con)); // Displays the exact SQL error
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['contact']) . "</td>";
        echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
        echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
        echo "<td>" . htmlspecialchars($row['blood_group']) . "</td>";
        echo "<td>
                <button class='btn btn-sm btn-primary edit-user' data-id='" . $row['id'] . "' data-type='donor'>Edit</button>
                <button class='btn btn-sm btn-danger delete-user' data-id='" . $row['id'] . "' data-type='donor'>Delete</button>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No donors found</td></tr>";
}

mysqli_close($con);
?>
