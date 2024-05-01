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
                    <th>Action</th> <!-- New column for the button -->
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
                        <form action="receipt.php" method="post" target="_blank">
                            <input type="hidden" name="payment_id" value="<?php echo $payment['payment_id']; ?>">
                            <input type="hidden" name="business_name" value="<?php echo $payment['business_name']; ?>">
                            <input type="hidden" name="business_type" value="<?php echo $payment['business_type']; ?>">
                            <input type="hidden" name="period" value="<?php echo $payment['period']; ?>">
                            <input type="hidden" name="date" value="<?php echo $payment['date']; ?>">
                            <input type="hidden" name="amount" value="<?php echo $payment['Amount']; ?>">
                            <input type="hidden" name="payment_method" value="<?php echo $payment['payment_method']; ?>">
                            <button type="submit" class="btn btn-info btn-sm">View Receipt</button>
                        </form>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once("foot.php"); ?>
<?php include("ffoot.php"); ?>
