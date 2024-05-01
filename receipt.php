<?php
include_once("head.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST['payment_id']) &&
        isset($_POST['business_name']) &&
        isset($_POST['business_type']) &&
        isset($_POST['period']) &&
        isset($_POST['date']) &&
        isset($_POST['amount']) &&
        isset($_POST['payment_method'])
    ) {
        $payment_id = $_POST['payment_id'];
        $business_name = $_POST['business_name'];
        $business_type = $_POST['business_type'];
        $period = $_POST['period'];
        $date = $_POST['date'];
        $amount = $_POST['amount'];
        $payment_method = $_POST['payment_method'];

        // Display receipt
        echo "<h2>Receipt</h2>";
        echo "<p><strong>Payment ID:</strong> $payment_id</p>";
        echo "<p><strong>Business Name:</strong> $business_name</p>";
        echo "<p><strong>Business Type:</strong> $business_type</p>";
        echo "<p><strong>Period:</strong> $period</p>";
        echo "<p><strong>Date:</strong> $date</p>";
        echo "<p><strong>Amount:</strong> $amount</p>";
        echo "<p><strong>Payment Method:</strong> $payment_method</p>";
        
        // Ask if user wants to print
        echo "<p>Do you want to print this receipt?</p>";
        echo "<button onclick=\"window.print()\">Print Receipt</button>";
    } else {
        echo "Insufficient data provided to generate receipt.";
    }
} else {
    echo "Invalid request method.";
}

include_once("foot.php");
include("ffoot.php");
?>
