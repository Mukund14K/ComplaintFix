<?php
session_start();
// Destroy current session [cite: 559]
session_unset();
session_destroy();
// Redirect user to login page [cite: 560]
header("Location: login.php");
exit();
?>