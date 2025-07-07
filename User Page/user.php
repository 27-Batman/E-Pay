<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../Login/login.php");
    exit();
}

// Retrieve user data from session
$userData = [
    'email' => $_SESSION['email'],
    'fullname' => $_SESSION['fullname'],
    'userid' => $_SESSION['userid'],
    'contact' => $_SESSION['contact'],
    'counter' => $_SESSION['counter'],
    'snno' => $_SESSION['snno']
];

// Database connection
$host = 'localhost';
$user = 'root';
$password = 'root';
$database = 'epay';

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle "PAY NOW" button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_now'])) {
    $payment_id = (int) $_POST['payment_id'];

    // Fetch payment data for the payment ID
    $fetchQuery = "SELECT * FROM payments WHERE id = ? AND sc_no = ?";
    $stmt = $conn->prepare($fetchQuery);
    $stmt->bind_param("is", $payment_id, $userData['snno']); // Added user snno for additional security
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $paymentData = $result->fetch_assoc();

        // Generate unique transaction ID
        $transaction_id = "TXN_" . time() . "_" . $userData['userid'];
        $total_amount = floatval($paymentData['payable_amt']);
        
        // eSewa Payment Redirection
        echo "
        <form id='esewa-payment-form' action='https://rc.esewa.com.np/epay/main' method='POST'>
            <input type='hidden' name='amt' value='{$total_amount}'>
            <input type='hidden' name='txAmt' value='0'>
            <input type='hidden' name='psc' value='0'>
            <input type='hidden' name='pdc' value='0'>
            <input type='hidden' name='tAmt' value='{$total_amount}'>
            <input type='hidden' name='pid' value='{$transaction_id}'>
            <input type='hidden' name='scd' value='EPAYTEST'>
            <input type='hidden' name='su' value='http://localhost/E-PAY/User%20Page/success.php'>
            <input type='hidden' name='fu' value='http://localhost/E-PAY/User%20Page/user.php'>
        </form>
        <script>
            document.getElementById('esewa-payment-form').submit();
        </script>";
        exit();
    } else {
        $_SESSION['message'] = "Payment record not found.";
        header("Location: user.php");
        exit();
    }
}

// Fetch data for Payment Table (Payments associated with the user)
$paymentQuery = "SELECT * FROM payments WHERE sc_no = ? ORDER BY due_date DESC";
$stmt = $conn->prepare($paymentQuery);
$stmt->bind_param("s", $userData['snno']); // Fetch payments based on user's sc_no
$stmt->execute();
$paymentResult = $stmt->get_result();

// Fetch data for Payment History Table (Payment History associated with the user)
$historyQuery = "SELECT * FROM payment_history WHERE sc_no = ? ORDER BY payment_date DESC";
$stmt = $conn->prepare($historyQuery);
$stmt->bind_param("s", $userData['snno']); // Fetch payment history based on user's snno
$stmt->execute();
$historyResult = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | O-EBS</title>
    <link rel="stylesheet" href="user.css">
    <style>
        .pay-now {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .pay-now:hover {
            background-color: #45a049;
        }
        .success-message {
            color: green;
            font-weight: bold;
        }
        .error-message {
            color: red;
            font-weight: bold;
        }
        .history-table, .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .history-table th, .history-table td,
        .payment-table th, .payment-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .history-table th, .payment-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="Header">
        <div class="logo">
            <h1>O-<br>EBS</h1>
        </div>
        <div class="welcome-text">
            <p>Welcome, <span><?= htmlspecialchars($userData['fullname']); ?></span></p>
        </div>
    </div>
    <div class="Dashboard">
        <h2>Dashboard</h2>
        <div class="Dashboard-line"></div>
        <div class="due-payment">
            <h3>Due Payment</h3>
            <div class="customer-info">
                <p>Customer Name: <span><?= htmlspecialchars($userData['fullname']); ?></span></p>
                <p>Counter: <span><?= htmlspecialchars($userData['counter']); ?></span></p>
                <p>Customer ID: <span><?= htmlspecialchars($userData['userid']); ?></span></p>
                <p>SC.No: <span><?= htmlspecialchars($userData['snno']); ?></span></p>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <p class="success-message"><?= htmlspecialchars($_SESSION['message']); ?></p>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <h2>Payment Details</h2>
            <table class="payment-table">
                <thead>
                    <tr>
                        <th>Due Date</th>
                        <th>SC.NO</th>
                        <th>Bill Amt</th>
                        <th>Rebate(R)/Fine(F)</th>
                        <th>Payable Amt</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $paymentResult->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['due_date']); ?></td>
                            <td><?= htmlspecialchars($row['sc_no']); ?></td>
                            <td><?= htmlspecialchars($row['bill_amt']); ?></td>
                            <td><?= htmlspecialchars($row['rebate_fine']); ?></td>
                            <td><?= htmlspecialchars($row['payable_amt']); ?></td>
                            <td>
                                <form action="user.php" method="POST">
                                    <input type="hidden" name="payment_id" value="<?= htmlspecialchars($row['id']); ?>">
                                    <button type="submit" name="pay_now" class="pay-now">Pay Now</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <h2>Payment History</h2>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Payment Date</th>
                        <th>SC.NO</th>
                        <th>Amount Paid</th>
                        <th>Transaction ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $historyResult->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['payment_date']); ?></td>
                            <td><?= htmlspecialchars($row['sc_no']); ?></td>
                            <td><?= htmlspecialchars($row['amount']); ?></td>
                            <td><?= htmlspecialchars($row['transaction_id']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <button class="log-out" onclick="window.location.href='logout.php';">LOG OUT</button>
        </div>
    </div>
</body>
</html>
