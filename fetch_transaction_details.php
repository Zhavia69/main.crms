<?php


session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['payment_id'])) {
     
        $paymentId = $_POST['payment_id'];
       
        $transactionDetails = fetch_transaction_details_from_database($paymentId);
        
        // Display receipt-like output
        if ($transactionDetails) {
            echo "<h2>Receipt</h2>";
            echo "<p><strong>Transaction ID:</strong> " . $transactionDetails['transaction_id'] . "</p>";
            echo "<p><strong>Business Name:</strong> " . $transactionDetails['business_name'] . "</p>";
            echo "<p><strong>Amount:</strong> $" . $transactionDetails['amount'] . "</p>";
            echo "<p><strong>Date:</strong> " . $transactionDetails['date'] . "</p>";
            echo "<p><strong>Payment Method:</strong> " . $transactionDetails['payment_method'] . "</p>";
            
            // Ask if user wants to print
            echo "<p>Do you want to print this receipt?</p>";
            echo "<button onclick=\"window.print()\">Print Receipt</button>";
        } else {
            // If transaction details not found, display an error message
            echo "Transaction details not found.";
        }
    } else {
        // If payment ID is not set, return an error message
        echo "Payment ID not provided.";
    }
} else {
    // If the request method is not POST, return an error message
    echo "Invalid request method.";
}

// Function to fetch transaction details from the database
function fetch_transaction_details_from_database($paymentId) {
    // Example: Implement your logic to fetch transaction details from the database
    // Example: You can use MySQLi or PDO to fetch data from the database
    
    // For demonstration purposes, returning sample transaction details
    $transactionDetails = array(
        'transaction_id' => '123456',
        'business_name' => 'Example Business',
        'amount' => '100.00',
        'date' => date("Y-m-d"),
        'payment_method' => 'Credit Card'
    );
    
    return $transactionDetails;
}
?>
