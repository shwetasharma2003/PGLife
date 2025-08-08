<?php
// $conn = mysqli_connect("127.0.0.1", "root", "", "pglife");

// if (mysqli_connect_errno()) {
//     // Throw error message based on ajax or not
//     echo "Failed to connect to MySQL! Please contact the admin.";
//     return;
// }


$host = "localhost";
$username = "root";
$password = "";
$database = "pg_life";

$conn = mysqli_connect($host, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
