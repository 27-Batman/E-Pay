<?php
$_SESSION['message'] = "Payment failed. Please try again!";
header("Location: dashboard.php");
exit();
?>
