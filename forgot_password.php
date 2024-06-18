<?php
session_start();
include_once('autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "bpayment";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = '';
$success_message = '';

// Function to generate OTP
function generateOTP($length = 6) {
    return rand(pow(10, $length-1), pow(10, $length)-1);
}

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    
    // Prepare SQL statement to fetch user by email
    $stmt = $conn->prepare("SELECT full_names, username FROM users WHERE username = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0){
        // If email doesn't exist, display a notification
        $error_message = "Sorry, the user does not exist. Please <a href='register.php'>register</a> to access our services.";
    } else {
        // If email exists, fetch user details
        $user_details = $result->fetch_assoc();
        
        // Generate OTP
//print_r($otp); // TO COMMENT OUT/DELETE
$otp = generateOTP(); // TO COMMENT OUT/DELETE
//$_SESSION['otp'] = $otp; // TO COMMENT OUT/DELETE
//$_SESSION['email'] = $email; // TO COMMENT OUT/DELETE
// Send OTP via email
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'sammuelryan4050@gmail.com'; // Replace with your Gmail email address
$mail->Password = 'kbxp hwbl icxk btux'; // Replace with your Gmail password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->setFrom('your_email@gmail.com', 'CRMS');
$mail->addAddress($email, $user_details['full_names']);

$mail->isHTML(true);
$mail->Subject = 'Password Reset OTP';
$mail->Body = "Your OTP for password reset is: $otp";

if($mail->send()){
    // Set OTP in session for verification
     $_SESSION['otp'] = $otp;
   $_SESSION['email'] = $email;
    // Redirect to password reset page directly
    header("Location: verify_otp.php?email=$email");
    exit();
} else {
     $error_message = "Failed to send OTP. Please try again later.";
}

    }
}

include_once("hhead.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .reset-password-container {
            max-width: 400px;
            margin: 0 auto;
            margin-top: 100px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .reset-password-container h1 {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="reset-password-container">
            <h1>Reset Password</h1>
            
            <?php if($error_message): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Enter your email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                <p>If you haven't registered yet, <a href="register.php">click here</a> to register.</p>
            </form>
        </div>
    </div>
</body>
</html>

