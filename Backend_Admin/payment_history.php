<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "epay";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['email'] !== "admin@gmail.com") {
    header("Location: ../Login/login.php");
    exit();
}

// Fetch all payment history records with user fullname (without amount_paid)
$query = "
    SELECT 
        ph.id, 
        ph.payment_date, 
        ph.sc_no, 
        ph.amount, 
        u.fullname 
    FROM 
        payment_history ph
    LEFT JOIN 
        users u ON ph.sc_no = u.snno
    ORDER BY 
        ph.payment_date DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Payment History</title>
    <link rel="stylesheet" href="payment_history.css">
</head>
<body>
    <div class="header">
            <li><a href="admin.php">Home</a></li>
            <li><a href="data.php">Data Entry</a></li>
            <li><a href="payment_history.php">Payment History</a></li>
            <li><a href="manage_user.php">Manage User</a></li>
            <li><a href="login_records.php">Login History</a></li>
    </div>

    <div class="sidebar">
        <ul>
            <li><a href="admin.php">Home</a></li>
            <li><a href="data.php">Data Entry</a></li>
            <li><a href="payment_history.php">Payment History</a></li>
            <li><a href="manage_user.php">Manage User</a></li>
        </ul>
    </div>

    <div class="content">
        <h2>Payment History</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Payment Date</th>
                    <th>SC No</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']); ?></td>
                        <td><?= htmlspecialchars($row['fullname']); ?></td>
                        <td><?= htmlspecialchars($row['payment_date']); ?></td>
                        <td><?= htmlspecialchars($row['sc_no']); ?></td>
                        <td><?= htmlspecialchars($row['amount']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>
