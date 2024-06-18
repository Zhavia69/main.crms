<?php 

session_start();

$error_message = '';

// Check if reset OTP has been sent
if(isset($_SESSION['reset_otp_sent']) && $_SESSION['reset_otp_sent'] == true){
    $reset_otp_message = "A reset OTP has been sent to your email address. Please check your inbox.";
} else {
    $reset_otp_message = "";
}

if(isset($_POST['submit'])){
    // Get OTP entered by the user
    $entered_otp = $_POST['otp'];
    
    // Check if OTP matches the one stored in session
    if(isset($_SESSION['otp']) && $_SESSION['otp'] == $entered_otp){
        // If OTP matches, proceed with password reset
        // Redirect to password reset page
        header("Location: reset_password.php");
        exit();
    } else {
        // If OTP doesn't match, display error message
        $error_message = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .verify-otp-container {
            max-width: 400px;
            margin: 0 auto;
            margin-top: 100px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .verify-otp-container h1 {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="verify-otp-container">
            <h1>CODE VERIFICATION</h1>
            
            <?php if($reset_otp_message): ?>
                <div class="alert alert-info"><?php echo $reset_otp_message; ?></div>
            <?php endif; ?>
            <?php if($error_message): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="otp">Enter OTP</label>
                    <input type="text" class="form-control" id="otp" name="otp" required>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>
