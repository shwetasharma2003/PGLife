<?php
// require("../includes/database_connect.php");

// $full_name = $_POST['full_name'];
// $phone = $_POST['phone'];
// $email = $_POST['email'];
// $password = $_POST['password'];
// $password = sha1($password);
// $college_name = $_POST['college_name'];
// $gender = $_POST['gender'];

// $sql = "SELECT * FROM users WHERE email='$email'";
// $result = mysqli_query($conn, $sql);
// if (!$result) {
//     $response = array("success" => false, "message" => "Something went wrong!");
//     echo json_encode($response);
//     return;
// }

// $row_count = mysqli_num_rows($result);
// if ($row_count != 0) {
//     $response = array("success" => false, "message" => "This email id is already registered with us!");
//     echo json_encode($response);
//     return;
// }

// $sql = "INSERT INTO users (email, password, full_name, phone, gender, college_name) VALUES ('$email', '$password', '$full_name', '$phone', '$gender', '$college_name')";
// $result = mysqli_query($conn, $sql);
// if (!$result) {
//     $response = array("success" => false, "message" => "Something went wrong!");
//     echo json_encode($response);
//     return;
// }

// $response = array("success" => true, "message" => "Your account has been created successfully!");
// echo json_encode($response);
// mysqli_close($conn);





session_start();
require_once "../includes/database_connect.php"; // Change this if your DB file is elsewhere

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $phone = trim($_POST["phone"]);
    $gender = $_POST["gender"];
    $college_name = trim($_POST["college_name"]);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $response["success"] = false;
        $response["message"] = "This email id is already registered with us!";
    } else {
        // Hash password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $insert_stmt = $conn->prepare("INSERT INTO users (full_name, email, password, phone, gender, college_name) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("ssssss", $full_name, $email, $hashed_password, $phone, $gender, $college_name);
        
        if ($insert_stmt->execute()) {
            $_SESSION["user_id"] = $insert_stmt->insert_id;
            $_SESSION["user_name"] = $full_name;

            $response["success"] = true;
            $response["message"] = "Signup successful";
        } else {
            $response["success"] = false;
            $response["message"] = "Something went wrong. Please try again.";
        }

        $insert_stmt->close();
    }

    $stmt->close();
} else {
    $response["success"] = false;
    $response["message"] = "Invalid request method.";
}

header("Content-Type: application/json");
echo json_encode($response);
