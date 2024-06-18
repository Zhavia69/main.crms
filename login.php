
<?php 
session_start();
include_once('autoload.php');

$error_message=''; 

function getDashboardValues($email){
    $servername = "localhost"; // Change this to your database server name
    $username = "root"; // Change this to your database username
    $password = ""; // Change this to your database password
    $dbname = "bpayment"; // Change this to your database name
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "SELECT count(id) AS payment_history_count FROM payment_history where email = '$email';";

    $result = $conn->query($sql);
    $_SESSION['payment_history_count']=0;
    if ($result->num_rows > 0) {
        $payment_history_count = ($result->fetch_assoc()['payment_history_count']);
        $_SESSION['payment_history_count']=$payment_history_count;
    }
}

if(isset($_SESSION['user_details']) && isset($_SESSION['user_id'])){
    $confirm=users::login_users($_SESSION['user_details']['username'],$_SESSION['user_details']['password']);
    $crows=$confirm['row_count'];
    if($crows >0){
        echo '<script type="text/javascript">window.location.href="home.php";</script>'; 
    }else{
        session_destroy();
        echo '<script type="text/javascript">window.location.href="login.php";</script>';
    }
} else {
    if(isset($_POST['login'])){
        extract($_POST);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $confirm_login=users::login_users($username);
        // print_r('Hashed Password : ' . strval($hashed_password).'<br>');
        // print_r('Password : ' . strval($password).'<br>');
        $hashed_password = $confirm_login['rows']['password'];

        $user_found = password_verify($password, $hashed_password);
        // print_r($user_found);
        // die();
        // $row_count=$confirm_login['row_count'];
        $rows=$confirm_login['rows'];
        if($user_found){

            getDashboardValues($username);
            $_SESSION['user_details']=$rows;
            $_SESSION['user_id']=$rows['user_id'];
            echo '<script type="text/javascript">window.location.href="home.php";</script>';
        } else { 
            $error_message="<div class='alert alert-danger'>Login Failed! Incorrect username/password.</div>"; 
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
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 0 auto;
            margin-top: 100px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-container h1 {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h1>Account Login</h1>
            <?php if(isset($error_message)) echo $error_message; ?>
            
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block mb-3" name="login">Login</button>

            </form>

            <!-- Forgot Password link -->
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
    </div>
</body>
</html>


