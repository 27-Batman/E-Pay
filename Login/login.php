<?php
session_start();
include 'db_connection.php'; // Database connection file

$error_message = "";  // Variable to store error message

// Admin credentials
$admin_email = "admin@gmail.com";
$admin_password = "admin@gmail.com";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']); // Sanitize email input
    $password = trim($_POST['password']); // Sanitize password input

    if (!empty($username) && !empty($password)) {
        // Capture IP address
        $ip_address = $_SERVER['REMOTE_ADDR'];

        // Check if the login is for admin
        if ($username === $admin_email && $password === $admin_password) {
            // Log admin login attempt
            $log_query = "INSERT INTO logins (email, ip_address) VALUES (?, ?)";
            $log_stmt = $conn->prepare($log_query);
            $log_stmt->bind_param("ss", $admin_email, $ip_address);
            $log_stmt->execute();
            $log_stmt->close();

            // Set session variables for admin
            $_SESSION['email'] = $admin_email;

            // Redirect to admin dashboard
            header("Location: ../Backend_Admin/admin.php");
            exit();
        } else {
            // Check if the user exists
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();

                    // Verify the password
                    if (password_verify($password, $user['password'])) {
                        // Log successful user login
                        $log_query = "INSERT INTO logins (email, ip_address) VALUES (?, ?)";
                        $log_stmt = $conn->prepare($log_query);
                        $log_stmt->bind_param("ss", $username, $ip_address);
                        $log_stmt->execute();
                        $log_stmt->close();

                        // Set session variables for the user
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['fullname'] = $user['fullname'];
                        $_SESSION['userid'] = $user['userid'];
                        $_SESSION['contact'] = $user['contact'];
                        $_SESSION['counter'] = $user['counter'];
                        $_SESSION['snno'] = $user['snno'];

                        // Redirect to user dashboard
                        header("Location: ../User Page/user.php");
                        exit();
                    } else {
                        $error_message = "Invalid email or password.";
                    }
                } else {
                    $error_message = "User does not exist.";
                }
                $stmt->close();
            } else {
                $error_message = "Database query failed: " . $conn->error;
            }
        }
    } else {
        $error_message = "Please fill in all fields.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Pay | Login</title>
    <link rel="stylesheet" href="login.css">
    <style>
        .error-message {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="logo-container">
                <img src="Icon.png" alt="icon" class="logo">
                <h1>E-<span>Pay</span></h1>
            </div>

            <!-- Display error message if login fails -->
            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <?= htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="login.php" method="post">
                <div class="input">
                    <label for="username">Email</label>
                    <input type="email" name="username" id="username" placeholder="Enter your Email" required>
                </div>
                <div class="input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your Password" required>
                </div>
                <button type="submit" class="login-btn">Log In</button>
            </form>

            <div class="register-link">
                <p>New to E-Pay? <a href="../Registration/register.php">Register Now</a></p>
            </div>
        </div>
    </div>
</body>
</html>
