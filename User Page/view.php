<?php
session_start();

// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['email'])) {
    header("Location: ../Login/login.php");
    exit();
}

// Retrieve user data from session
$email = $_SESSION['email'];
$fullname = $_SESSION['fullname'];
$userid = $_SESSION['userid'];
$contact = $_SESSION['contact'];
$counter = $_SESSION['counter'];
$snno = $_SESSION['snno'];

// Connect to the database (replace with your database connection settings)
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "epay"; // Update with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch bill details using the bill_id passed via the URL
$bill_id = $_GET['bill_id'];

// Fetch the bill details from the database
$sql = "SELECT * FROM bills WHERE bill_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bill_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $bill = $result->fetch_assoc();
} else {
    die("Bill not found.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Details | E-Pay</title>
    <link rel="stylesheet" href="bill_details.css">
</head>
<body>
    <div class="bill-details-container">
        <h2>Bill Details</h2>
        <div class="user-info">
            <p><strong>Logged in as:</strong> <?php echo htmlspecialchars($fullname); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Customer ID:</strong> <?php echo htmlspecialchars($userid); ?></p>
            <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($contact); ?></p>
            <p><strong>Counter:</strong> <?php echo htmlspecialchars($counter); ?></p>
            <p><strong>SC.No:</strong> <?php echo htmlspecialchars($snno); ?></p>
        </div>

        <div class="bill-info">
            <p><strong>Bill Number:</strong> <?php echo htmlspecialchars($bill['bill_id']); ?></p>
            <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($bill['customer_name']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($bill['location']); ?></p>
            <p><strong>Due Date:</strong> <?php echo htmlspecialchars($bill['due_date']); ?></p>
            <p><strong>Bill Date:</strong> <?php echo htmlspecialchars($bill['bill_date']); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($bill['phone_number']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($bill['email']); ?></p>
            <p><strong>Amount:</strong> <?php echo htmlspecialchars($bill['amount']); ?></p>
        </div>

        <form action="generate_pdf.php" method="POST">
            <input type="hidden" name="bill_id" value="<?php echo htmlspecialchars($bill['bill_id']); ?>">
            <button type="submit" class="generate-pdf">Generate PDF</button>
        </form>
    </div>
</body>
</html>
