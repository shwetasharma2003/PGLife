<?php
// session_start();
// require("../includes/database_connect.php");

// $email = $_POST['email'];
// $password = $_POST['password'];
// $password = sha1($password);

// $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
// $result = mysqli_query($conn, $sql);
// if (!$result) {
//     $response = array("success" => false, "message" => "Something went wrong!");
//     echo json_encode($response);
//     return;
// }

// $row_count = mysqli_num_rows($result);
// if ($row_count == 0) {
    
//     $response = array("success" => false, "message" => "Login failed! Invalid email or password.");
//     echo json_encode($response);
    
//     return;
// }

// $row = mysqli_fetch_assoc($result);
// $_SESSION['user_id'] = $row['id'];
// $_SESSION['full_name'] = $row['full_name'];
// $_SESSION['email'] = $row['email'];

// $response = array("success" => true, "message" => "Login successful!");
// echo json_encode($response);
// mysqli_close($conn);




session_start();
require_once "../includes/database_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
           $_SESSION["full_name"] = $user["full_name"];


            // ✅ Redirect to dashboard
            header("Location: ../dashboard.php");
            exit;
        } else {
            echo "❌ Incorrect password.";
        }
    } else {
        echo "❌ Email not found.";
    }

    $stmt->close();
}
?>
