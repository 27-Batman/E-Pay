<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "epay";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for new payment
if (isset($_POST['submit_payment'])) {
    $sc_no = trim($_POST['sc_no']);
    $due_date = $_POST['due_date'];
    $bill_amt = $_POST['bill_amt'];
    $rebate_fine = $_POST['rebate_fine'];
    $payable_amt = $_POST['payable_amt'];

    // Check if SC No exists in the users table
    $check_user_query = "SELECT snno FROM users WHERE snno = ?";
    $stmt = $conn->prepare($check_user_query);
    $stmt->bind_param("s", $sc_no);
    $stmt->execute();
    $user_result = $stmt->get_result();
    
    if ($user_result->num_rows == 0) {
        echo "<script>alert('Error: SC No does not exist in the users table.');</script>";
    } else {
        // Check for duplicate entry in payments
        $check_query = "SELECT * FROM payments WHERE sc_no = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("s", $sc_no);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Error: Duplicate entry! This SC No already exists in payments.');</script>";
        } else {
            // Insert payment details
            $payment_query = "INSERT INTO payments (sc_no, due_date, bill_amt, rebate_fine, payable_amt) 
                              VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($payment_query);
            $stmt->bind_param("ssdss", $sc_no, $due_date, $bill_amt, $rebate_fine, $payable_amt);
            
            if ($stmt->execute()) {
                echo "<script>alert('Payment added successfully!');</script>";
            } else {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
            }
        }
    }
}

// Get the current date in YYYY-MM-DD format
$current_date = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | E-Pay</title>
    <link rel="stylesheet" href="admin.css">
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="text"], input[type="date"], input[type="number"], input[type="submit"] {
            padding: 8px;
            margin: 8px 0;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="number"] {
            -moz-appearance: textfield;
        }
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
    <script>
        function updatePayableAmount() {
            var billAmount = parseFloat(document.getElementById('bill_amt').value) || 0;
            var rebateFine = parseFloat(document.getElementById('rebate_fine').value) || 0;
            var payableAmount = billAmount + rebateFine;
            document.getElementById('payable_amt').value = payableAmount.toFixed(2);
        }
    </script>
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
    </div>
    <div class="content">
        <h1>Admin Dashboard</h1>
        <h3>Enter Payment Details</h3>
        <form method="POST">
            <div class="form-group">
                <label for="sc_no">SC No:</label>
                <input type="text" name="sc_no" id="sc_no" required><br>
            </div>
            <div class="form-group">
                <label for="due_date">Due Date:</label>
                <input type="date" name="due_date" id="due_date" value="<?php echo $current_date; ?>" required><br>
            </div>
            <div class="form-group">
                <label for="bill_amt">Bill Amount:</label>
                <input type="number" step="0.01" name="bill_amt" id="bill_amt" oninput="updatePayableAmount()" required><br>
            </div>
            <div class="form-group">
                <label for="rebate_fine">Rebate/Fine:</label>
                <input type="number" step="0.01" name="rebate_fine" id="rebate_fine" oninput="updatePayableAmount()" required><br>
            </div>
            <div class="form-group">
                <label for="payable_amt">Payable Amount:</label>
                <input type="number" step="0.01" name="payable_amt" id="payable_amt" readonly required><br>
            </div>
            <input type="submit" name="submit_payment" value="Add Payment">
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>
