
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

      <!-- Rest of form -->

    </form>

  </div>


</div>
<?php

include_once('foot.php');

?>