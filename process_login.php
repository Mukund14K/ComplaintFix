<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. Search user in database [cite: 508]
    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // 2. Check whether credentials are correct [cite: 509]
        if (password_verify($password, $user['password'])) {
            // 3. If valid, create user session [cite: 510]
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            
            // 4. Redirect user to dashboard [cite: 511]
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid Credentials'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('User not found'); window.location='login.php';</script>";
    }
}
?>