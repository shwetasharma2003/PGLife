<?php
require "../includes/database_connect.php";

$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$college_name = $_POST['college_name'];
$gender = $_POST['gender'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Check if passwords match
if ($password !== $confirm_password) {
    echo "Passwords do not match!";
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if user already exists (by email or phone)
$check_user = "SELECT * FROM users WHERE email='$email' OR phone='$phone'";
$result = mysqli_query($conn, $check_user);

if (mysqli_num_rows($result) > 0) {
    echo "User already exists with this email or phone.";
    exit();
}

// Insert into users table
$query = "INSERT INTO users (email, password, full_name, phone, gender, college_name) 
          VALUES ('$email', '$hashed_password', '$full_name', '$phone', '$gender', '$college_name')";

if (mysqli_query($conn, $query)) {
       $_SESSION['signup_success'] = true;
     header("Location: ../index.php");
   
    exit();
} else {
    echo "Signup failed: " . mysqli_error($conn);
}
?>
