<?php 
session_start();

// Check if user is logged in and OTP is verified
if(!isset($_SESSION['otp']) || !isset($_SESSION['email'])){
    // If not logged in or OTP is not verified, redirect to OTP verification page
    header("Location: verify_otp.php");
    exit();
}

$error_message = '';
$success_message = '';

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

if(isset($_POST['reset_password'])){
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if($new_password === $confirm_password){
        // Get email from session
        $email = $_SESSION['email'];
        
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update password in the database
        $stmt_update = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt_update->bind_param("ss", $hashed_password, $email);
        
        if ($stmt_update->execute()) {
            // Password updated successfully
            $success_message = "You have successfully updated the user password. Please proceed to <a href='login.php'>login</a>.";
        } else {
            // Error occurred while updating password
            $error_message = "Error updating password: " . $stmt_update->error;
            // Log the error for debugging
            error_log("Error updating password: " . $stmt_update->error);
        }
    } else {
        $error_message = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
            
            <?php if($success_message): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if($error_message): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Create new password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                </div>
                <button type="submit" class="btn btn-primary" name="reset_password">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
