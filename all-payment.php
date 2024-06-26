<?php 
include_once('head.php');

// Establish a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bpayment";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch client details from the payment_history table
$sql = "SELECT * FROM payment_history";

$result = $conn->query($sql);
?>

<div class='row' id='row' style='margin-top:24px;padding:4px;'>
    <div class='col-sm-12 col-md-12 col-xs-12 col-lg-12'>
        <ul class='breadcrumb'>
            <li class='breadcrumb-item'><a href='all-payment.php'><i class='fa fa-list'></i>&nbsp;&nbsp;All PAYMENT</a></li>
        </ul>
    </div>
</div>

<style>
    #row {
        margin-top: 24px;
    }
</style>

<div class='row' id='row'>
    <div class='col-sm-12 col-md-12 col-xs-12 col-lg-12'>
        <div class='btn-group'>
            <a href='add-payment.php' class='btn btn-info'><i class='fa fa-plus'></i> ADD NEW</a>
            <a class='btn btn-primary' href='all-payment.php' class='btn btn-default'><i class='fa fa-refresh'></i> payment List</a>
        </div>
    </div>
</div>

<div class='row' id='searchbar'>
    <div class='col-sm-6 col-md-6 col-xs-12 col-lg-6'>
        <form class='form-inline'>
            <i class='fa fa-calendar'> Search Between Dates: </i>
            <input type='date' name='date1' class='form-control' required='required'>
            <input type='date' name='date2' class='form-control' required='required'>
            <button type='submit' class='btn btn-default'>&nbsp;<i class='fa fa-search'></i>&nbsp;&nbsp;SEARCH</button>
        </form>
    </div>
    <div class='col-sm-6 col-md-6 col-xs-12 col-lg-6'>
        <form class='form-inline'>
            <select name='type' class='form-control select' required='required'>
                <option value='business_name'>BUSINESS NAME</option>
                <option value='date'>DATE</option>
                <option value='period'>PERIOD</option>
            </select>
            <input type='text' name='query' class='form-control' required='required'>
            <button style='margin-left:4px;border:1px solid lightgrey;' name='check' type='submit' class='btn btn-info'>
                <i class='fa fa-search'></i> SEARCH
            </button>
        </form>
    </div>
</div>

<div class='row' id='row'>
    <div class='col-sm-12 col-md-12 col-xs-12 col-lg-12'>
        <?php
        if (isset($_GET['id'])) {
            $r = new payment($_GET['id']);
            $r->delete_payment();
            echo '<script>alert("ITEM WAS DELETED!"); window.location.href="all-payment.php";</script>';
        }
        $alldata = payment::read_payment();
        $column = payment::payment_constants();
        if (isset($_GET['check']) && isset($_GET['type']) && isset($_GET['query'])) {
            if (in_array($_GET['type'], $column)) {
                $alldata = payment::search_payment($_GET['type'], $_GET['query']);
            } else {
                $alldata = payment::read_payment();
            }
        }
        ?>
        <center><b><?php echo count($alldata); ?> Records Found. <?php
                if (isset($_GET['type']) && isset($_GET['query'])) {
                    echo 'search results for: ' . $_GET['type'] . ' / ' . $_GET['query'];
                }
                ?></b></center><p><hr><p>
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
                    <th class='Delete'><i class='fa fa-trash'></i> Delete</th>

               </tr>
            </thead>
            <tbody>
               <?php 
               // Check if there are any payment records
               if ($result->num_rows > 0) {
                   // Output data of each row
                   while($payment = $result->fetch_assoc()) {
               ?>
                    <tr>
                    <td>
                        <?php echo str_pad($payment['id'], 5, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo $payment['business_name']; ?></td>
                        <td><?php echo $payment['business_type']; ?></td>
                        <td><?php echo $payment['period']; ?></td>
                        <td><?php echo $payment['date']; ?></td>
                        <td><?php echo $payment['amount']; ?></td>
                        <td><?php echo $payment['payment_method']; ?></td>
                        
                        <td>
                            <form action="receipt.php" method="post" target="_blank">
                                <input type="hidden" name="payment_id" value="<?php echo $payment['id']; ?>">
                                <input type="hidden" name="business_name" value="<?php echo $payment['business_name']; ?>">
                                <input type="hidden" name="business_type" value="<?php echo $payment['business_type']; ?>">
                                <input type="hidden" name="period" value="<?php echo $payment['period']; ?>">
                                <input type="hidden" name="date" value="<?php echo $payment['date']; ?>">
                                <input type="hidden" name="amount" value="<?php echo $payment['amount']; ?>">
                                <input type="hidden" name="payment_method" value="<?php echo $payment['payment_method']; ?>">
                                <button type="submit" class="btn btn-info btn-sm">View Receipt</button>
                            </form>
                        </td>
                        <td class='Delete'>
                            <a href='all-payment.php?id=<?php echo $payment['id']; ?>' class='btn btn-danger'>
                            <i class='fa fa-trash'></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='8'>No payments found</td></tr>";
                }
                // Close the database connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once("foot.php"); ?>
