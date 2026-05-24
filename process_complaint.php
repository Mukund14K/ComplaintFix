<?php
// Start session and check authentication as per SDD Security Design
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $_SESSION['user_id'];
    
    // Sanitize incoming textual array payloads against SQL Injection paths
    $cat = mysqli_real_escape_string($conn, $_POST['category']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $pri = mysqli_real_escape_string($conn, $_POST['priority']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    
    // Evaluate anonymous state bit tracking rules
    $is_anonymous = isset($_POST['anonymous']) ? 1 : 0;
    $date = date('Y-m-d');
    $status = "Pending"; // Base system lifecycle baseline configuration state

    $attachment_path = ""; // Default empty string state if no file proof is submitted

    // 🔒 File Upload Handler Engine Boundary Loop
    if (isset($_FILES['evidence']) && $_FILES['evidence']['error'] == 0) {
        $target_dir = "uploads/";
        
        // Infrastructure Shield: Build the directory folder on disk if it doesn't exist yet
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Namespace Collision Protection: Affix a time epoch signature to make the filename unique
        $file_name = time() . "_" . basename($_FILES["evidence"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_size = $_FILES["evidence"]["size"];

        // System Constraints: Block file payloads scaling above the 5MB maximum restriction ceiling
        if ($file_size <= 5242880) {
            // Commit raw binary stream allocation out of temporary memory into your server uploads disk sector
            if (move_uploaded_file($_FILES["evidence"]["tmp_name"], $target_file)) {
                $attachment_path = mysqli_real_escape_string($conn, $target_file);
            } else {
                echo "<script>alert('Server write execution failure. Please check directory permissions.'); window.history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('Upload Rejected: Evidence file size exceeds maximum allowed 5MB limit.'); window.history.back();</script>";
            exit();
        }
    }

    // 🛠️ Updated Execution Statement matching custom columns schema
    $sql = "INSERT INTO complaints (user_id, category, description, priority, status, created_at, location, attachment, is_anonymous) 
            VALUES ('$uid', '$cat', '$desc', '$pri', '$status', '$date', '$location', '$attachment_path', '$is_anonymous')";

    if ($conn->query($sql) === TRUE) {
        // Smooth view transition routing to success landing confirmation screen
        header("Location: submission_success.php");
        exit();
    } else {
        echo "Database Engine Processing Error: " . $conn->error;
    }
} else {
    // Kick user back to submission if trying to hit page via explicit GET routing requests
    header("Location: submit_complaint.php");
    exit();
}
?>