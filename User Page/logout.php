<?php
// logout.php

// Start the session
session_start();

// Destroy the session to log out
session_destroy();

// Redirect to login page
header("Location:../Login/login.php") ;
exit();
?>
