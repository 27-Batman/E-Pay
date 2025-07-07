<?php
// Check if sc_no, user_id, or user_name is passed
if (isset($_GET['sc_no']) || isset($_GET['user_id']) || isset($_GET['user_name'])) {
    $connection = new mysqli("hostname", "username", "password", "database");

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $sql = "";
    $stmt = null;

    if (isset($_GET['sc_no'])) {
        // Fetch details by SC No
        $snno = $_GET['scnno'];
        $sql = "SELECT fullname, email, counter, userid, snno FROM users WHERE snno = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $snno);
    } elseif (isset($_GET['user_id'])) {
        // Fetch details by User ID
        $user_id = $_GET['userid'];
        $sql = "SELECT fullname, gmail, contact, userid, counter, snno FROM users WHERE userid = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $userid);
    } elseif (isset($_GET['user_name'])) {
        // Fetch details by User Name
        $user_name = $_GET['user_name'];
        $sql = "SELECT fullname, gmail, contact, userid, counter, snno FROM users WHERE fullname = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $user_name);
    }

    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $details = $result->fetch_assoc();
            echo json_encode($details);
        } else {
            echo json_encode(null);
        }

        $stmt->close();
    }

    $connection->close();
}
?>
