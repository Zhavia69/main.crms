<style>
    .text-danger strong {
        color: #9f181c;
    }

    .receipt-main {
        background: #ffffff none repeat scroll 0 0;
        border-bottom: 12px solid #333333;
        border-top: 12px solid #9f181c;
        margin-top: 50px;
        margin-bottom: 50px;
        padding: 40px 30px !important;
        position: relative;
        box-shadow: 0 1px 21px #acacac;
        color: #333333;
        font-family: open sans;
    }

    .receipt-main p {
        color: #333333;
        font-family: open sans;
        line-height: 1.42857;
    }

    .receipt-footer h1 {
        font-size: 15px;
        font-weight: 400 !important;
        margin: 0 !important;
    }

    .receipt-main::after {
        background: #414143 none repeat scroll 0 0;
        content: "";
        height: 5px;
        left: 0;
        position: absolute;
        right: 0;
        top: -13px;
    }

    .receipt-main thead {
        background: #414143 none repeat scroll 0 0;
    }

    .receipt-main thead th {
        color: #fff;
    }

    .receipt-right h5 {
        font-size: 16px;
        font-weight: bold;
        margin: 0 0 7px 0;
    }

    .receipt-right p {
        font-size: 12px;
        margin: 0px;
    }

    .receipt-right p i {
        text-align: center;
        width: 18px;
    }

    .receipt-main td {
        padding: 9px 20px !important;
    }

    .receipt-main th {
        padding: 13px 20px !important;
    }

    .receipt-main td {
        font-size: 18px;
        font-weight: initial !important;
    }

    .receipt-main td p:last-child {
        margin: 0;
        padding: 0;
    }

    .receipt-main td h2 {
        font-size: 20px;
        font-weight: 900;
        margin: 0;
        text-transform: uppercase;
    }

    .receipt-header-mid .receipt-left h1 {
        font-weight: 100;
        margin: 34px 0 0;
        text-align: center;
        text-transform: uppercase;
    }

    .receipt-header-mid {
        margin: 24px 0;
        overflow: hidden;
    }

    #container {
        background-color: #dcdcdc;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
    }

    @media print {
        .print-button {
            display: none; /* Hide print button when printing */
        }
    }
    
</style>

<?php
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
        // Assign POST data to variables
        $payment_id = $_POST['payment_id'];
        $business_name = $_POST['business_name'];
        $business_type = $_POST['business_type'];
        $period = $_POST['period'];
        $date = $_POST['date'];
        $amount = $_POST['amount'];
        $payment_method = $_POST['payment_method'];

        // Display receipt
        echo "<div class=\"receipt-main\">";
        echo "<div class=\"print-button\"><button onclick=\"window.print()\">Print</button></div>"; // Print button moved inside a div
        echo "<h3><center>KIAMBU COUNTY REVENUE MANAGEMENT SYSTEM</center></h3>";
        echo "<div class=\"receipt-header receipt-header-mid\">";
        echo "<div class=\"receipt-right\">";
        echo "<h5>RECEIPT NUMBER: $payment_id </h5>";
        echo "<h5>BUSINESS NAME: $business_name </h5>";
        echo "<h5>PAYMENT DATE.: $date </h5>";
        echo "</div>";
        echo "<div class=\"receipt-left\">";
        echo "<h1>Payment Receipt</h1>";
        echo "</div>";
        echo "</div>";

        echo "<div>";
        echo "<table width=\"100%\">";
        // Output table rows with data
        echo "<tr><th>Payment Details</th><th>Payment Values</th></tr>"; // Titles for columns
        echo "<tr><td>Business Type:</td><td>$business_type</td></tr>";
        echo "<tr><td>Period:</td><td>$period</td></tr>";
        echo "<tr><td>Amount:</td><td>$amount</td></tr>";
        echo "<tr><td>Payment Method:</td><td>$payment_method</td></tr>";
        echo "</table>";
        echo "</div>";

        echo "<div class=\"receipt-footer\">";
        echo "<div class=\"receipt-right\">";
        echo "<p><b>Date Printed:</b> " . date('d M, Y') . "</p>";
        echo "<h5 style=\"color: rgb(140, 140, 140);\">Thank you for your business!</h5>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    } else {
        echo "Insufficient data provided to generate receipt.";
    }
} else {
    echo "Invalid request method.";
}
?>
