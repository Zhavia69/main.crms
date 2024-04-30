<?php include_once("hhead.php");
 include_once('autoload.php');
  ?>
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
           
        }
        .register-container {
            max-width: 500px;
            margin: 0 auto;
            margin-top: 50px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .register-container h1 {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <h1>Register</h1>
            <?php 
            $role = "client"; 
            if (isset($_POST['go'])) {
                extract($_POST);
                $user = new users();
                $user->create_users($full_names, $username, $password, $tel, $business_name, $type_of_business, $addresses, $role);
                echo "<div class='alert alert-success'>Registration successful. Please <a href='login.php'>click here to login</a>.</div>";
            } 
            ?>

<form method="POST">
                <div class="form-group">
                    <label for="full_names">Full Names</label>
                    <input type="text" class="form-control" id="full_names" name="full_names" required>
                </div>
                <div class="form-group">
                    <label for="username">Email</label>
                    <input type="email" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="tel">Phone</label>
                    <input type="text" class="form-control" id="tel" name="tel" required>
                </div>
                <div class="form-group">
                    <label for="business_name">Business Name</label>
                    <input type="text" class="form-control" id="business_name" name="business_name" required>
                </div>
                <div class="form-group">
                    <label for="type_of_business">Business Type</label>
                    <input type="text" class="form-control" id="type_of_business" name="type_of_business" required>
                </div>
                <div class="form-group">
                    <label for="addresses">Address</label>
                    <input type="text" class="form-control" id="addresses" name="addresses" required>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block" name="go">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>
     <?php include_once("ffoot.php"); ?>