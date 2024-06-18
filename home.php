<?php include_once("head.php");

// Database configuration
$db_host = 'localhost';
$db_name = 'bpayment';
$db_user = 'root';
$db_pass = '';

// Establish database connection using PDO
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}

// Assuming $_SESSION['user_details']['email'] contains the email of the logged-in user
$email = $_SESSION['user_details']['username'];

// Initialize variables to store counts
$mpesaCount = 0;
$creditCardCount = 0;
$totalAmountSpent = 0;
$paymentCount = 0;

// Check if the user's role is 'client'
if ($_SESSION['user_details']['role'] == 'client') {
    // Construct SQL query to count payments for the current user
    $paymentStmt = $pdo->prepare("SELECT COUNT(*) AS payment_count FROM payment_history WHERE email = :email");
    $paymentStmt->execute(['email' => $email]);
    $paymentCount = $paymentStmt->fetch(PDO::FETCH_ASSOC)['payment_count'];

    // Construct SQL query to count Mpesa transactions for the current user
    $mpesaStmt = $pdo->prepare("SELECT COUNT(*) AS mpesa_count FROM payment_history WHERE email = :email AND payment_method = 'mpesa'");
    $mpesaStmt->execute(['email' => $email]);
    $mpesaCount = $mpesaStmt->fetch(PDO::FETCH_ASSOC)['mpesa_count'];

    // Construct SQL query to count credit card transactions for the current user
    $creditCardStmt = $pdo->prepare("SELECT COUNT(*) AS credit_card_count FROM payment_history WHERE email = :email AND payment_method = 'credit card'");
    $creditCardStmt->execute(['email' => $email]);
    $creditCardCount = $creditCardStmt->fetch(PDO::FETCH_ASSOC)['credit_card_count'];

    // Construct SQL query to calculate total amount spent by the current user
    $totalAmountStmt = $pdo->prepare("SELECT IFNULL(SUM(amount), 0) AS total_amount_spent FROM payment_history WHERE email = :email");
    $totalAmountStmt->execute(['email' => $email]);
    $totalAmountSpent = $totalAmountStmt->fetch(PDO::FETCH_ASSOC)['total_amount_spent'];

} elseif ($_SESSION['user_details']['role'] == 'admin') {
    // Construct SQL query to count Mpesa transactions for all users
    $mpesaStmt = $pdo->prepare("SELECT COUNT(*) AS mpesa_count FROM payment_history WHERE payment_method = 'mpesa'");
    $mpesaStmt->execute();
    $mpesaCount = $mpesaStmt->fetch(PDO::FETCH_ASSOC)['mpesa_count'];

    // Construct SQL query to count credit card transactions for all users
    $creditCardStmt = $pdo->prepare("SELECT COUNT(*) AS credit_card_count FROM payment_history WHERE payment_method = 'credit card'");
    $creditCardStmt->execute();
    $creditCardCount = $creditCardStmt->fetch(PDO::FETCH_ASSOC)['credit_card_count'];

    // Construct SQL query to calculate total amount spent by all users
    $totalAmountStmt = $pdo->prepare("SELECT IFNULL(SUM(amount), 0) AS total_amount_spent FROM payment_history");
    $totalAmountStmt->execute();
    $totalAmountSpent = $totalAmountStmt->fetch(PDO::FETCH_ASSOC)['total_amount_spent'];

     // Construct SQL query to count all payments
     $paymentStmt = $pdo->query("SELECT COUNT(*) AS payment_count FROM payment_history");
     $paymentCount = $paymentStmt->fetch(PDO::FETCH_ASSOC)['payment_count'];
}
?>

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
        margin-bottom: 20px;
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

    .container-fluid {
        padding-top: 20px;
    }

    h2, h5 {
        color: #333;
    }

    hr {
        margin-top: 20px;
        margin-bottom: 20px;
        border: 0;
        border-top: 1px solid #eee;
    }

    .row {
        margin-right: 0;
        margin-left: 0;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2>Home</h2>   
            <h5>Welcome <?php echo $_SESSION['user_details']['full_names']; ?>, Glad to have you.</h5>
        </div>
    </div>
    <hr />

    <div class="row">
        <div class="col-md-3 col-sm-6">           
            <div class="panel panel-back noti-box">
                <span class="icon-box bg-color-green set-icon">
                    <i class="fa fa-briefcase"></i>
                </span>
                <div class="text-box">
                    <p class="main-text">Transactions</p>
                    <p class="value"><?php echo $paymentCount; ?></p>
                    <p class="text-muted">Revenue Collected</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">           
            <div class="panel panel-back noti-box">
                <span class="icon-box bg-color-blue set-icon">
                    <i class="fa fa-money"></i>
                </span>
                <div class="text-box">
                    <p class="main-text">Mpesa</p>
                    <p class="value"><?php echo $mpesaCount;?>  </p>
                    <p class="text-muted">Total Transactions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">           
            <div class="panel panel-back noti-box">
                <span class="icon-box bg-color-blue set-icon">
                    <i class="fa fa-credit-card"></i>
                </span>
                <div class="text-box">
                    <p class="main-text">Credit Card </p>
                    <p class="value"><?php echo $creditCardCount;?></p>
                    <p class="text-muted">Total Transactions</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">           
            <div class="panel panel-back noti-box">
                <span class="icon-box bg-color-brown set-icon">
                    <i class="fa fa-laptop"></i>
                </span>
                <div class="text-box">
                    <p class="main-text">Total</p>
                    <p class="value"><?php echo $totalAmountSpent;?></p>
                    <p class="text-muted">Money Paid</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel-back" style="padding: 10px;">
                <h3 class="main-text">Notifications</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel-back" style="padding: 20px; margin-top: 20px;">
                <h3 class="main-text">System Information</h3>
                <p class="text-muted">This system helps you manage your revenue and business transactions efficiently. You can track pending payments, view revenue collected, and manage your businesses seamlessly.</p>
            </div>
        </div>
    </div>
</div>


