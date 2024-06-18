<?php

require_once('path/to/Daraja.php');

// Retrieve the callback data sent by Safaricom
$callbackData = file_get_contents('php://input');

// Log the callback data for debugging or record-keeping
file_put_contents('callback_log.txt', $callbackData);

// Decode the JSON callback data
$callbackDataArray = json_decode($callbackData, true);

// Extract relevant information from the callback data
$resultCode = $callbackDataArray['Body']['stkCallback']['ResultCode'];
$merchantRequestID = $callbackDataArray['Body']['stkCallback']['MerchantRequestID'];
$checkoutRequestID = $callbackDataArray['Body']['stkCallback']['CheckoutRequestID'];
$resultDesc = $callbackDataArray['Body']['stkCallback']['ResultDesc'];

// Perform any necessary actions based on the callback result
if ($resultCode == 0) {
    // Payment was successful
    // Perform your success actions here
    echo "Payment successful. MerchantRequestID: $merchantRequestID, CheckoutRequestID: $checkoutRequestID";
} else {
    // Payment failed
    // Perform your failure actions here
    echo "Payment failed. ResultCode: $resultCode, ResultDesc: $resultDesc";
}

?>
