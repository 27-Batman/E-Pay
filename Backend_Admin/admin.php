<?php
session_start();
require_once 'db_connection.php'; // Database connection

// Check if admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    header("Location: ../Login/login.php");
    exit();
}

// Fetch total payments
$total_payments_query = "SELECT SUM(payable_amt) FROM payments";
$total_payments_result = mysqli_query($conn, $total_payments_query);
$total_payments = mysqli_fetch_row($total_payments_result)[0] ?? 0;
$total_payments = $total_payments > 0 ? $total_payments : 0; // Prevent negative values

// Fetch total users
$total_users_query = "SELECT COUNT(*) FROM users";
$total_users_result = mysqli_query($conn, $total_users_query);
$total_users = mysqli_fetch_row($total_users_result)[0] ?? 0;

// Fetch recent transactions
$recent_transactions_query = "SELECT id, payable_amt, due_date, created_at FROM payments ORDER BY created_at DESC LIMIT 5";
$recent_transactions_result = mysqli_query($conn, $recent_transactions_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | E-Pay</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .header {
            background-color: #06c;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .logo {
            font-size: 24px;
            font-weight: bold;
        }
        .header .logo span {
            color: #ff4500;
        }
        .header .logout {
            background-color: #ff4500;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        .header .logout:hover {
            background-color: #ff5733;
        }
        .sidebar {
            width: 200px;
            background-color: #06c;
            position: fixed;
            top: 0;
            bottom: 0;
            padding: 20px;
            color: white;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 15px 0;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            padding: 10px;
            display: block;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .sidebar ul li a:hover {
            background-color: #0047ab;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
        }
        .dashboard-card {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .chart-container {
            width: 80%;
            max-width: 500px;
            margin: auto;
        }
        .footer {
            background-color: #343a40;
            color: white;
            padding: 15px 0;
            text-align: center;
        }
        @media screen and (max-width: 768px) {
            .header { flex-direction: column; align-items: flex-start; }
            .sidebar { width: 100%; position: static; margin-bottom: 20px; }
            .content { margin-left: 0; padding: 10px; }
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">E-<span>Pay</span></div>
        <div class="welcome">Welcome, Admin</div>
        <a href="Login/logout.php" class="logout">Log Out</a>
    </div>

    <div class="sidebar">
        <ul>
            <li><a href="admin.php">Home</a></li>
            <li><a href="data.php">Data Entry</a></li>
            <li><a href="payment_history.php">Payment History</a></li>
            <li><a href="manage_user.php">Manage User</a></li>
            <li><a href="login_records.php">Login History</a></li>
        </ul>
        <div>
            <h3>Quick Info</h3>
            <p>Total Users: <strong><?php echo $total_users; ?></strong></p>
            <p>Total Payments: <strong>Rs.<?php echo number_format($total_payments, 2); ?></strong></p>
        </div>
    </div>

    <div class="content">
        <h1>Admin Dashboard</h1>

        <div class="dashboard-card">
            <h3>Total Payments</h3>
            <p>Rs.<?php echo number_format($total_payments, 2); ?></p>
        </div>

        <div class="dashboard-card">
            <h3>Total Users</h3>
            <p><?php echo $total_users; ?></p>
        </div>

        <div class="dashboard-card">
            <h3>Recent Transactions</h3>
            <table style="width: 100%; border-collapse: collapse; background: white;">
                <thead>
                    <tr style="background-color: #06c; color: white;">
                        <th>ID</th>
                        <th>Amount</th>
                        <th>Due Date</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($recent_transactions_result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>Rs.<?php echo number_format($row['payable_amt'], 2); ?></td>
                            <td><?php echo $row['due_date']; ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="dashboard-card">
            <h3>Payments Chart</h3>
            <div class="chart-container">
                <canvas id="paymentChart"></canvas>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2024 O-EBS | All Rights Reserved</p>
    </div>

    <script>
        var ctx = document.getElementById('paymentChart').getContext('2d');
        var paymentChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Paid', 'Unpaid'],
                datasets: [{
                    label: 'Payments',
                    data: [
                        <?php echo $total_payments; ?>, 
                        <?php echo max(10000 - $total_payments, 0); ?>
                    ],
                    backgroundColor: ['#36A2EB', '#FF5733']
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'top' } } }
        });
    </script>

</body>
</html>
