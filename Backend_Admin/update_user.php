<?php
session_start();
include 'db_connection.php'; // Adjust path as needed

if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    header("Location: ../Login/login.php");
    exit();
}

// Handle Edit User functionality
if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];

    // Fetch user details for editing
    $query = "SELECT * FROM users WHERE userid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // Update user information on form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
        $fullname = trim($_POST['fullname']);
        $email = trim($_POST['email']);
        $contact = trim($_POST['contact']);
        $counter = trim($_POST['counter']);
        $snno = trim($_POST['snno']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);
        $gender = trim($_POST['gender']);

        // Check if passwords match
        if ($password !== $confirm_password) {
            $error_message = "Passwords do not match!";
        } else {
            // Hash the password securely if updated
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Update the user information
            $update_query = "UPDATE users SET fullname = ?, email = ?, contact = ?, counter = ?, snno = ?, password = ?, gender = ? WHERE userid = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("sssssssi", $fullname, $email, $contact, $counter, $snno, $hashed_password, $gender, $userid);

            if ($stmt->execute()) {
                $success_message = "User updated successfully!";
            } else {
                $error_message = "Failed to update user. Please try again.";
            }
            $stmt->close();
        }
    }
}

// Fetch total number of users and payments for the admin dashboard
$userQuery = "SELECT COUNT(*) AS total_users FROM users";
$userResult = $conn->query($userQuery);
$totalUsers = $userResult->fetch_assoc()['total_users'] ?? 0;

$paymentQuery = "SELECT SUM(payable_amt) AS total_payments FROM payments";
$paymentResult = $conn->query($paymentQuery);
$totalPayments = $paymentResult->fetch_assoc()['total_payments'] ?? 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | E-Pay</title>
    <link rel="stylesheet" href="payment_history.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .header {
            background-color: #06c;
            color: #fff;
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

        .header .welcome {
            font-size: 18px;
        }

        .header .logout {
            background-color: #ff4500;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
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
            color: #fff;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #fff;
            padding: 10px;
            display: block;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: blue;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
        }

        .content h1 {
            color: #333;
        }

        .content .card {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .content form input,
        .content form select,
        .content form button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            box-sizing: border-box;
        }

        .content form label {
            margin-top: 10px;
        }

        .content .error-message,
        .content .success-message {
            color: red;
            font-weight: bold;
        }

        .content .back-button {
            display: inline-block;
            margin-top: 20px;
            color: #06c;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
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
        <h1>Admin Dashboard</h1>
        
        <!-- Quick Stats Card -->
        <div class="card">
            <h2>Quick Stats</h2>
            <p>Total Users: <strong><?php echo $totalUsers; ?></strong></p>
            <p>Total Payments: <strong>Rs.<?php echo number_format($totalPayments, 2); ?></strong></p>
        </div>

        <!-- Edit User Form -->
        <h2>Edit User Information</h2>

        <?php if (isset($success_message)): ?>
            <p class="success-message"><?= htmlspecialchars($success_message); ?></p>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="update_user" value="1">

            <label for="fullname">Full Name:</label>
            <input type="text" name="fullname" id="fullname" value="<?= htmlspecialchars($user['fullname']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']); ?>" required>

            <label for="contact">Contact:</label>
            <input type="text" name="contact" id="contact" value="<?= htmlspecialchars($user['contact']); ?>" required>

            <label for="counter">Counter:</label>
            <input type="text" name="counter" id="counter" value="<?= htmlspecialchars($user['counter']); ?>" required>

            <label for="snno">SC.No:</label>
            <input type="text" name="snno" id="snno" value="<?= htmlspecialchars($user['snno']); ?>" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
                <option value="Male" <?= $user['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?= $user['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?= $user['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>

            <button type="submit">Update User</button>
        </form>

        <a href="manage_user.php" class="back-button">Back to Manage Users</a>
    </div>
</body>
</html>
