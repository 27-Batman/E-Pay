<?php
if (isset($_GET['q']) && $_GET['q'] === 'su') {
    $pid = $_GET['oid']; // Payment ID
    $amt = $_GET['amt']; // Amount Paid
    $refId = $_GET['refId']; // Transaction Reference ID

    $url = "https://uat.esewa.com.np/epay/transrec";
    $data = [
        'amt' => $amt,
        'rid' => $refId,
        'pid' => $pid,
        'scd' => 'YOUR_MERCHANT_CODE',
    ];

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    if (strpos($response, '<response_code>Success</response_code>') !== false) {
        // Mark payment as successful and move data to payment history
        $_SESSION['message'] = "Payment successful!";
    } else {
        $_SESSION['message'] = "Payment verification failed!";
    }
    header("Location: dashboard.php");
    exit();
}
?>
