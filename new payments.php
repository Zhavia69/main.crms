<?php
include_once('head.php');
include_once('autoload.php');

$business_name = $date = $period = $status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    extract($_POST);

    // Validate and sanitize input data
    $business_name = htmlspecialchars(trim($business_name));
    $date = htmlspecialchars(trim($date));
    $period = htmlspecialchars(trim($period));
    $status = htmlspecialchars(trim($status));

    // Validate input fields (add your validation logic here)

    // Create new payment instance
    $newPayment = new payment();

    // Insert new payment into the database
    $result = $newPayment->create_payment($business_name, $date, $period, $status, 'YES', '', '');

    if ($result === true) {
        echo '<script>swal({
            title: "Success!",
            text: "Info Saved!",
            icon: "success", 
            button: "Close",
        })</script>';
    } else {
        echo '<script>swal({
            title: "Error!",
            text: "Failed to save payment info.",
            icon: "error", 
            button: "Close",
        })</script>';
    }
}
?>

<div class='row' id='row'>
    <div class='col-sm-12 col-md-12 col-xs-12 col-lg-12'>
        <ul class='breadcrumb'>
            <li class='breadcrumb-item'><a href='all-payment.php'><i class='fa fa-list'></i>&nbsp;&nbsp;PAYMENT Records</a></li>
            <li class='breadcrumb-item'><a href='#'><i class='fa fa-list'></i>&nbsp;&nbsp;New&nbsp;payment</a></li>
        </ul>
    </div>
</div>

<div class='row' id='row'>
    <div class='col-sm-12 col-md-12 col-xs-12 col-lg-12'>
        <form class='form' method='POST' id='form' enctype='multipart/form-data'>
            <!-- Add payment form fields here -->
            <div class='form-group'>
                <label for='business_name'>Business Name</label>
                <input type='text' name='business_name' id='business_name' class='form-control' required>
            </div>
            <div class='form-group'>
                <label for='date'>Date of Payment</label>
                <input type='date' name='date' id='date' class='form-control' required>
            </div>
            <div class='form-group'>
                <label for='period'>Period</label>
                <select name='period' id='period' class='form-control' required>
                    <option value='1'>1 Month</option>
                    <option value='3'>3 Months</option>
                    <option value='6'>6 Months</option>
                    <option value='12'>1 Year</option>
                </select>
            </div>
            <div class='form-group'>
                <label for='status'>Status</label>
                <select name='status' id='status' class='form-control' required>
                    <option value='Pending'>Pending</option>
                    <option value='Approved'>Approved</option>
                    <option value='Rejected'>Rejected</option>
                </select>
            </div>
            <button type='submit' name='save' class='btn btn-primary'>Save Payment</button>
        </form>
    </div>
</div>

<?php
include_once('foot.php');
?>
