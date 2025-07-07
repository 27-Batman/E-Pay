<?php
session_start();
include 'db_connection.php'; // Ensure this file contains a valid connection

// Check database connection
if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    header("Location: ../Login/login.php");
    exit();
}

// Fetch total number of users
$userQuery = "SELECT COUNT(*) AS total_users FROM users";
$userResult = $conn->query($userQuery);
$totalUsers = $userResult->fetch_assoc()['total_users'] ?? 0;

// Fetch total payments
$paymentQuery = "SELECT SUM(payable_amt) AS total_payments FROM payments";
$paymentResult = $conn->query($paymentQuery);
$totalPayments = $paymentResult->fetch_assoc()['total_payments'] ?? 0;

// Fetch all users
$query = "SELECT * FROM users";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Handle new user addition
$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $userid = (int) ($conn->query("SELECT MAX(userid) AS max_id FROM users")->fetch_assoc()['max_id'] ?? 0) + 1;
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $counter = trim($_POST['counter']);
    $snno = trim($_POST['snno']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $gender = trim($_POST['gender']);

    // Validation
    if (empty($fullname) || empty($email) || empty($contact) || empty($counter) || empty($snno) || empty($password) || empty($confirm_password) || empty($gender)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (!preg_match('/^[0-9]{10,}$/', $contact)) {
        $error = "Contact number must be at least 10 digits and numeric!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (!preg_match("/^[a-zA-Z\s]{2,50}$/", $fullname)) {
        $error = "Name must contain only letters and spaces (2-50 characters)!";
    } else {
        // **Check for duplicate email or contact**
        $checkDuplicate = "SELECT * FROM users WHERE email = ? OR contact = ?";
        $stmt = $conn->prepare($checkDuplicate);
        $stmt->bind_param("ss", $email, $contact);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Duplicate data found! Email or Contact number already exists.";
        }
    }

    // If no errors, proceed with insertion
    if (empty($error)) {
        // Hash password for security
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert new user into the database
        $insertQuery = "INSERT INTO users (userid, fullname, email, contact, counter, snno, password, gender) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("isssssss", $userid, $fullname, $email, $contact, $counter, $snno, $hashedPassword, $gender);

        if ($stmt->execute()) {
            echo "<script>alert('New user added successfully!'); window.location.href='manage_user.php';</script>";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Admin Dashboard</title>
    <link rel="stylesheet" href="manage_users.css">
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
        </ul>
        <div>
            <h3>Quick Info</h3>
            <p>Total Users: <strong><?php echo $totalUsers; ?></strong></p>
            <p>Total Payments: <strong>Rs.<?php echo number_format($totalPayments, 2); ?></strong></p>
        </div>
    </div>
    <div class="content">
        <h1>Manage Users</h1>
        <h2>Add New User</h2>

        <?php if (!empty($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>

        <form method="POST">
            <input type="hidden" name="add_user" value="1">
            <label>User ID (Auto-generated):</label>
            <input type="text" value="<?php echo (int) ($conn->query("SELECT MAX(userid) AS max_id FROM users")->fetch_assoc()['max_id'] ?? 0) + 1; ?>" readonly>
            
            <label>Full Name:</label>
            <input type="text" name="fullname" required>
            
            <label>Email:</label>
            <input type="email" name="email" required>
            
            <label>Contact:</label>
            <input type="text" name="contact" required>
            
            <label>Counter:</label>
            <input type="text" name="counter" required>
            
            <label>SC.No:</label>
            <input type="text" name="snno" required>
            
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>
            
            <label>Gender:</label>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            
            <button type="submit">Add User</button>
        </form>

        <h3>Existing Users</h3>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $user['userid'] ?></td>
                        <td><?= $user['fullname'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['contact'] ?></td>
                        <td><a href="update_user.php?userid=<?= $user['userid'] ?>">Edit</a> | 
                            <a href="delete_user.php?userid=<?= $user['userid'] ?>">Delete</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
