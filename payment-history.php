<?php
include_once("head.php");
$error_message = "";

// Include your database connection file
include_once("includes/classes/class.DB.php");

?>
<div class="row">
    <div class="col-md-12">
        <h2>Payment Home Page</h2>
        <h5><i class="fa fa-money"></i> My Payments</h5>
    </div>
    <hr />
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>S.no</th>
                    <th>Business name</th>
                    <th>Business Type</th>
                    <th>Period</th>
                    <th>Date</th>
                    <th>Amount (KES)</th>
                    <th>Payment Method</th>
                    <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <?php 
               // Check if $_SESSION is set and user_details exists before accessing it
               if (isset($_SESSION['user_details']) && isset($_SESSION['user_details']['role'])) {
                   if ($_SESSION['user_details']['role'] !== 'admin') {
                       $p = payment::search_marched_payment('business_name', $_SESSION['user_details']['business_name']);
                   } else {
                       $p = payment::read_payment();
                   }
               } else {
                   $p = array(); // Set empty array if $_SESSION or user_details are not set
               }

               if (!is_array($p)) {
                   $p = array();
               }

               foreach ($p as $payment) {  
                   ?>
                    <tr>
                        <td><?php echo $payment['payment_id']; ?></td>
                        <td><?php echo $payment['business_name']; ?></td>
                        <td><?php echo $payment['business_type']; ?></td>
                        <td><?php echo $payment['period']; ?></td>
                        <td><?php echo $payment['date']; ?></td>
                        <td><?php echo $payment['Amount']; ?></td>
                        <td><?php echo $payment['payment_method']; ?></td>
                        <td>
                            <button class="btn btn-info btn-sm" onclick="printReceipt('<?php echo $payment['payment_id']; ?>')">Receipt</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function printReceipt(paymentId) {
        // AJAX request to fetch transaction details
        $.ajax({
            url: 'fetch_transaction_details.php', // Replace with actual file to fetch details
            method: 'POST',
            data: {payment_id: paymentId},
            success: function(response) {
                // Create a hidden form and submit it to download as Word document
                var form = document.createElement('form');
                form.method = 'post';
                form.action = 'receipt.php'; // Replace with actual file to generate Word document
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'transaction_details';
                input.value = response; // Transaction details received from AJAX response
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            }
        });
    }
</script>

<?php include_once("foot.php"); ?>
<?php include("ffoot.php"); ?>
