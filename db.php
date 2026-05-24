<?php
$conn = new mysqli("localhost", "root", "", "complaintfix");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
