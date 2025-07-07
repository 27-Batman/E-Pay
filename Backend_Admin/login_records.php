<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "epay";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Ensure this file is in the correct location

// Check database connection
if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['email'] !== "admin@gmail.com") {
    header("Location: ../Login/login.php");
    exit();
}

// Fetch login records
$query = "SELECT * FROM logins ORDER BY login_time DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Login Records</title>
    <link rel="stylesheet" href="login_data.css">
</head>
<body>
    <div class="header">
        <div class="logo">E-<span>Pay</span></div>
        <div class="welcome">Welcome, Admin</div>
        <a href="logout.php" class="logout">Log Out</a>
    </div>

    <div class="sidebar">
        <ul>
        <li><a href="admin.php">Home</a></li>
            <li><a href="data.php">Data Entry</a></li>
            <li><a href="payment_history.php">Payment History</a></li>
            <li><a href="manage_user.php">Manage User</a></li>
            <li><a href="login_records.php">Login History</a></li>
        </ul>
    </div>

    <div class="content">
        <h2>Login Records</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Login Time</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['login_time']); ?></td>
                        <td><?= htmlspecialchars($row['ip_address']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>
