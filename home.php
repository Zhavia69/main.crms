<?php include_once("head.php"); ?>

<style>
    /* CSS for fixed footer */
    .footer {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        background-color: #008000; /* Kenyan flag's green */
        color: #ffffff; /* White text */
        text-align: center;
        padding: 10px 0;
        font-family: 'Times New Roman', Times, serif; /* Use a standard font */
    }
    .panel-back {
    border-radius: 10px;
    padding: 20px;
    background-color: #f5f5f5;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: box-shadow 0.3s ease;
}

.panel-back:hover {
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}

.icon-box {
    display: inline-block;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    text-align: center;
    line-height: 50px;
    margin-right: 15px;
}

.set-icon {
    color: #fff;
}

.text-box {
    display: inline-block;
    vertical-align: middle;
}

.main-text {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 5px;
}

.value {
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

.text-muted {
    font-size: 14px;
    color: #999;
}

</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2>Home</h2>   
            <h5>Welcome/Karibu <?php echo $_SESSION['user_details']['full_names']; ?>, Glad to see you back.</h5>
        </div>
    </div>
    <hr />

    <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-6">           
        <div class="panel panel-back noti-box">
            <span class="icon-box bg-color-red set-icon">
                <i class="fa fa-money"></i>
            </span>
            <div class="text-box">
                <p class="main-text">New</p>
                <p class="value"><?php echo count(payment::search_marched_payment("status","Pending Approval", $_SESSION['user_details']['user_id'])); ?></p>
                <p class="text-muted">Pending Payments</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">           
        <div class="panel panel-back noti-box">
            <span class="icon-box bg-color-green set-icon">
                <i class="fa fa-briefcase"></i>
            </span>
            <div class="text-box">
                <p class="main-text">Transactions</p>
                <p class="value"><?php echo count(payment::read_payment($_SESSION['user_details']['user_id'])); ?></p>
                <p class="text-muted">Revenue Collected</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">           
        <div class="panel panel-back noti-box">
            <span class="icon-box bg-color-blue set-icon">
                <i class="fa fa-info-circle"></i>
            </span>
            <div class="text-box">
                <p class="main-text">Mpesa Transactions</p>
                <p class="value"><?php
                $mpesaTransactions = 0;
                $payments = payment::read_payment($_SESSION['user_details']['user_id']);
                foreach ($payments as $payment) {
                    if ($payment['payment_method'] === 'mpesa') {
                        $mpesaTransactions++;
                    }
                }
                echo $mpesaTransactions;
                ?></p>
                <p class="text-muted">Via Mpesa Today</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">           
        <div class="panel panel-back noti-box">
            <span class="icon-box bg-color-brown set-icon">
                <i class="fa fa-laptop"></i>
            </span>
            <div class="text-box">
                <p class="main-text">Total</p>
                <p class="value"><?php echo count(users::read_users($_SESSION['user_details']['user_id'])); ?></p>
                <p class="text-muted">Businesses</p>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12"> 
        <div class="row">
            <div class="col-md-12">
                <div class="panel-back" style="padding: 10px;">
                    <h3 class="main-text">Notifications</h3>
                </div>
            </div>
        </div>  
    </div>
</div>

<div class="row">
    <div class="col-12"> 
        <div class="row">
            <div class="col-md-12">
                <div class="panel-back" style="padding: 20px; margin-top: 20px;">
                    <h3 class="main-text">System Information</h3>
                    <p class="text-muted">This system helps you manage your revenue and business transactions efficiently. You can track pending payments, view revenue collected, and manage your businesses seamlessly.</p>
                </div>
            </div>
        </div>  
    </div>
</div>




<!-- Fixed Footer -->
<div class="footer">
    <?php include_once("foot.php"); ?>
    <?php include("ffoot.php"); ?>
</div>


