
<?php

include_once('head.php');

?><?php

    include_once('autoload.php');


    ?>

<div class='row' id='row'>
  <div class='col-sm-12 col-md-12 col-xs-12 col-lg-12'>
    <ul class='breadcrumb'>
      <li class='breadcrumb-item'><a href='all-payment.php'><i class='fa fa-list'></i>&nbsp;&nbsp;Payment Records</a></li>
      <li class='breadcrumb-item'><a href='new payments.php'><i class='fa fa-list'></i>&nbsp;&nbsp;New&nbsp;Payment</a></li>
    </ul>

  </div>


</div>
<?php


$business_name = $date = $period = $status = '';

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $data = payment::get_payment($id);
  extract($data);
}



?>
<div class='row' id='row'>
  <div class='col-sm-12 col-md-12 col-xs-12 col-lg-12'>
    <?php
    if (isset($_POST['save']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
      extract($_POST);
    ?>

      <div class='alert alert-info'>
        <?php if (isset($_GET['id'])) {
          $ss = new payment($_GET['id']);
          echo $ss->update_payment($business_name, $date, $period, $status);
          echo '<script>swal({
          title: "Success!",
          text: "Info Updated!",
          icon: "success", 
          button: "Close",
        })</script>';
        } else {
          $ss = new payment();
          echo $ss->create_payment($business_name, $date, $period, $status, 'YES', '', '');
          echo '<script>swal({
            title: "Success!",
            text: "Info Saved!",
            icon: "success", 
            button: "Close",
          })</script>';
        }

        ?>

      </div>
    <?php


    }

    ?>

    <form class='form' method='POST' id='form' enctype='multipart/form-data'>

    <form method="POST">
            <div class="row form-group">
                <div class="col-12 col-sm-4">Business Name</div>
                <div class="col-12 col-sm-9">
                    <input type="text" name="business_name" value="<?php echo $_SESSION['user_details']['business_name']; ?>" class="form-control" required >
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Category</div>
                <div class="col-12 col-sm-9">
                    <select name="business_type" class="form-control" required onchange="updateAmount(this.value)">
                        <option value="Option 0">choose your category</option>
                        <option value="Event Centers">Event Centers</option>
                        <option value="Fines and Penalties">Fines and Penalties</option>
                        <option value="Market Levy">Market Levy</option>
                        <option value="Security">Security</option>
                        <option value="Waste Management">Waste Management</option>
                        <option value="fees and permits">fees and permits</option>
                        <option value="Express Way">Express Way</option>
                    </select>
                </div>
</div>


            <div class="row form-group">
                <div class="col-12 col-sm-4">Date of Payment</div>
                <div class="col-12 col-sm-9">
                    <input type="date" name="date" class="form-control" required>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Period</div>
                <div class="col-12 col-sm-9">
                    <select name="Period" class="form-control" required>
                        <option value="">Select Period</option>
                        <option value="2023/2024">2023/2024</option>
                        <option value="2024/2025">2024/2025</option>
                        <option value="2025/2026">2025/2026</option>
                    </select>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Payment Method</div>
                <div class="col-12 col-sm-9">
                    <select name="payment_method" class="form-control" required>
                        <option value="">Select mode of payment</option>
                        <option value="Mpesa">Mpesa</option>
                        <option value="Credit Card">Credit</option>
                    </select>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Amount Paid (KES)</div>
                <div class="col-12 col-sm-9">
                    <input type="number" name="Amount" id="amount" class="form-control" required>
                    
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Phone Number</div>
                <div class="col-12 col-sm-9">
                    <input type="tel" name="phone_number" class="form-control" required>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Email</div>
                <div class="col-12 col-sm-9">
                    <input type="email" name="email" class="form-control" required>
                </div>
            </div>

            <button type="submit" name="go" class="btn btn-primary btn-lg btn-block">SUBMIT PAYMENT</button>
        </form>
    </div>
</div>
<script>
    function updateAmount(businessType) {
        var amountField = document.getElementById('amount');
        switch (businessType) {
            case 'Event Centers':
                amountField.value = '10000'; // Set the amount based on the business type
                break;
            case 'Fines and Penalties':
                amountField.value = '5000';
                break;
            case 'Market Levy':
                amountField.value = '3000';
                break;
            case 'Security':
                amountField.value = '2000';
                break;
            case 'Waste Management':
                amountField.value = '5000';
                break;
            case 'fees and permits':
                amountField.value = '12000';
                break;
            case 'Express Way':
                amountField.value = '6000';
                break;
           
        }
    }
</script>

    </form>

  </div>


</div>
<?php

include_once('foot.php');

?>