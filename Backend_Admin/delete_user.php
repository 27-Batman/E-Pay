<?php
session_start();
include 'db_connection.php'; // Adjust the path as needed

// Check if admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    header("Location: ../Login/login.php");
    exit();
}

// Check if userid is set
if (isset($_GET['userid']) && is_numeric($_GET['userid'])) {
    $userid = $_GET['userid'];

    // Prepare DELETE query
    $deleteQuery = "DELETE FROM users WHERE userid = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $userid);

    if ($stmt->execute()) {
        // Redirect back with success message
        header("Location: manage_user.php?delete_success=1");
        exit();
    } else {
        echo "Error deleting user: " . $conn->error;
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
