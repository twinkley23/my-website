<?php
session_start();         // Start the session
session_destroy();       // Destroy all session data
header("Location: login.php");  // Redirect to login
exit();                  // Stop the script
?>
