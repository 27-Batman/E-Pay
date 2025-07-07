<?php
session_start();

// Check if required data is received from eSewa
if (!isset($_GET['oid']) || !isset($_GET['amt']) || !isset($_GET['refId'])) {
    die("Invalid payment response.");
}

// Retrieve transaction details
$transaction_id = $_GET['oid'];  // Transaction ID (from eSewa)
$amount_paid = $_GET['amt'];      // Amount paid
// $reference_id = $_GET['refId'];   // Reference ID (from eSewa) - Remove this if not required

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

// Retrieve user details
$sn_no = $_SESSION['snno'];  // SC.NO from session

// Find the most recent due payment for this user
$fetchPaymentQuery = "SELECT id FROM payments WHERE sc_no = ? ORDER BY due_date DESC LIMIT 1";
$stmt = $conn->prepare($fetchPaymentQuery);
$stmt->bind_param("i", $sn_no);
$stmt->execute();
$stmt->bind_result($payment_id);
$stmt->fetch();
$stmt->close();

if (!$payment_id) {
    $_SESSION['message'] = "No due payments found!";
    header("Location: user.php");
    exit();
}

// Display success message via JavaScript alert
echo "<script>
    alert('Payment successful! Transaction ID: $transaction_id');
    window.location.href = 'user.php'; // Redirect to user page after alert
</script>";

// Update `payment_history` table with the transaction details after user clicks "OK"
$insertQuery = "INSERT INTO payment_history (transaction_id, amount, payment_date, sc_no) SELECT ?, ?, NOW(), sc_no FROM payments WHERE id = ?";
$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("sdi", $transaction_id, $amount_paid, $payment_id); // Insert into payment_history
$stmt->execute();

// Delete the record from `payments` table after inserting it into payment_history
$deleteQuery = "DELETE FROM payments WHERE id = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("i", $payment_id);
$stmt->execute();

$stmt->close();
$conn->close();
exit();
?>
