<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Receipt</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
    .receipt-container {
        margin: 20px;
    }
    .receipt-header {
        text-align: center;
        margin-bottom: 20px;
    }
    .receipt-details {
        border: 1px solid #ccc;
        padding: 20px;
        margin-bottom: 20px;
    }
</style>
</head>
<body>

<div class="receipt-container">
    <div class="receipt-header">
        <h2>Receipt</h2>
    </div>

    <div class="receipt-details">
        <div class="row">
            <div class="col-md-6">
                <strong>Transaction ID:</strong> <span id="transaction_id"></span><br>
                <strong>Date:</strong> <span id="transaction_date"></span>
            </div>
            <div class="col-md-6 text-right">
                <strong>Amount:</strong> <span id="transaction_amount"></span><br>
                <strong>Payment Method:</strong> <span id="transaction_method"></span>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <strong>Business Name:</strong> <span id="business_name"></span><br>
                <strong>Business Type:</strong> <span id="business_type"></span><br>
                <strong>Period:</strong> <span id="period"></span>
            </div>
        </div>
    </div>

    <div class="text-center">
        <button class="btn btn-primary" onclick="window.print()">Print Receipt</button>
    </div>
</div>

<script>
    // Function to populate transaction details
    function populateTransactionDetails(details) {
        document.getElementById("transaction_id").textContent = details.payment_id || '';
        document.getElementById("transaction_date").textContent = details.date || '';
        document.getElementById("transaction_amount").textContent = details.amount || '';
        document.getElementById("transaction_method").textContent = details.payment_method || '';
        document.getElementById("business_name").textContent = details.business_name || '';
        document.getElementById("business_type").textContent = details.business_type || '';
        document.getElementById("period").textContent = details.period || '';
    }

    // Call the function to populate details when the page loads
    populateTransactionDetails(<?php echo json_encode($_POST['transaction_details']); ?>);
</script>

</body>
</html>
