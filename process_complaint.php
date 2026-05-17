<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $_SESSION['user_id'];
    $cat = $_POST['category'];
    $sub = $_POST['subject'];
    $desc = $_POST['description'];
    $pri = $_POST['priority'];
    $date = date('Y-m-d'); // Current date [cite: 78]

    $sql = "INSERT INTO complaints (user_id, category, subject, description, priority, created_at) 
            VALUES ('$uid', '$cat', '$sub', '$desc', '$pri', '$date')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Complaint Submitted Successfully!'); window.location='dashboard.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>