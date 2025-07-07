<?php

/**
 * WHMCS Payment Gateway Callback File
 *
 * Handles payment confirmation callbacks from the payment gateway.
 */

// Define the path to the WHMCS root directory
require_once __DIR__ . '/../../../../init.php';
require_once __DIR__ . '/../../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../../includes/invoicefunctions.php';

// Detect module name from filename
$gatewayModuleName = basename(__FILE__, '.php');

// Fetch gateway configuration parameters
$gatewayParams = getGatewayVariables($gatewayModuleName);

// Die if module is not active
if (!$gatewayParams['type']) {
    die("Module Not Activated");
}

// Retrieve data returned in payment gateway callback
$success        = isset($_POST["ResponseCode"]) ? $_POST["ResponseCode"] : null;
$invoiceId      = isset($_POST["RefId"]) ? $_POST["RefId"] : null;
$transactionId  = isset($_POST["TransactionId"]) ? $_POST["TransactionId"] : null;
$paymentAmount  = isset($_POST["TranAmount"]) ? $_POST["TranAmount"] : null;
$paymentFee     = "0";

// Validate callback data
if (!$invoiceId || !$transactionId || !$paymentAmount) {
    die("Invalid callback parameters");
}

// Determine transaction status
$transactionStatus = ($success == 0) ? 'Success' : 'Failure';

// Validate invoice ID
$invoiceId = checkCbInvoiceID($invoiceId, $gatewayParams['name']);

// Check for duplicate transaction ID
checkCbTransID($transactionId);

// Log transaction for debugging
logTransaction($gatewayParams['name'], $_POST, $transactionStatus);

if ($success == 0) {
    // Apply payment to the invoice
    addInvoicePayment(
        $invoiceId,
        $transactionId,
        $paymentAmount,
        $paymentFee,
        $gatewayModuleName
    );

    // Redirect to success URL
    $successUrl = $gatewayParams['systemurl'] . "/viewinvoice.php?id=" . $invoiceId . "&paymentsuccess=true";
    header("Location: $successUrl");
    exit;
} else {
    // Redirect to failure URL
    $failUrl = $gatewayParams['systemurl'] . "/viewinvoice.php?id=" . $invoiceId;
    header("Location: $failUrl");
    exit;
}

?>
