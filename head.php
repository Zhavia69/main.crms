<?php 
session_start(); 
$role = 'user'; 
if(!isset($_SESSION['user_id']) && !isset($_SESSION['user_details'])){ 
    header('location: login.php'); 
} 
include_once('autoload.php'); 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>County Revenue Management System</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <style type="text/css">
        body{
            zoom: 81% !important;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                </button>
                <a class="navbar-brand" href="home.php">CRMS</a> 
            </div>
            <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;">
                Today : <?php echo date('d-m-Y'); ?> &nbsp;
                <a href="logout.php" class="btn btn-danger square-btn-adjust">Logout</a> 
            </div>
        </nav>
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="assets/img/find_user.png" class="user-image img-responsive"/>
                    </li>
                    <?php if ($_SESSION['user_details']['role'] != 'admin') { ?>
                    <li>
                        <a href="update-client.php"><i class="fa fa-pencil fa-3x"></i> Update Client Details</a>
                    </li>
                    <?php } ?>
                    <li>
                        <a href="home.php"><i class="fa fa-dashboard fa-3x"></i> Dashboard</a>
                    </li>
                    <?php if ($_SESSION['user_details']['role'] != 'admin') { ?>
                    <li>
                        <a href="make-payment.php"><i class="fa fa-desktop fa-3x"></i> Make Payment</a>
                    </li>
                    <?php } ?>
                    <li>
                        <a href="payment-history.php"><i class="fa fa-qrcode fa-3x"></i> Payment History</a>
                    </li>
                    <?php if ($_SESSION['user_details']['role'] == 'admin') { ?>
                    <li>
                        <a href="all-payment.php"><i class="fa fa-desktop fa-3x"></i>  Payments Tab</a>
                    </li>
                    <li>
                        <a href="all-users.php"><i class="fa fa-users fa-3x"></i> System Users</a>
                    </li>
                    <li>
                        <a href="payment report.php"><i class="fa fa-desktop fa-3x"></i>  Payments Report</a>
                    </li>
                    <?php } ?>
                    <li>
                        <a href="logout.php"><i class="fa fa-power-off fa-3x"></i> Exit </a>
                    </li>       
                </ul>
            </div>
        </nav>  
        <div id="page-wrapper">
            <div id="page-inner">
                    </body>
