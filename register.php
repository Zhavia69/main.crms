<?php 
include_once("hhead.php");
include_once('autoload.php');

// Rest of your PHP code
$role = "client";

// Function to validate email format
function validateEmail($email) {
    // Email format validation using regular expression
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}

// Function to validate password complexity and length
function validatePassword($password) {
    // Minimum length requirement
    $min_length = 8;
    // Complexity requirements
    $uppercase_required = true;
    $lowercase_required = true;
    $number_required = true;
    $special_char_required = true;
    // Common patterns and sequences to avoid
    $common_patterns = array('123456', 'password', 'qwerty');
    $sequential_chars = array('abcdefghijklmnopqrstuvwxyz', 'zyxwvutsrqponmlkjihgfedcba');

    // Check length
    if (strlen($password) < $min_length) {
        return false;
    }

    // Check complexity
    if ($uppercase_required && !preg_match('/[A-Z]/', $password)) {
        return false;
    }
    if ($lowercase_required && !preg_match('/[a-z]/', $password)) {
        return false;
    }
    if ($number_required && !preg_match('/[0-9]/', $password)) {
        return false;
    }
    if ($special_char_required && !preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
        return false;
    }

    // Check for common patterns and sequences
    foreach ($common_patterns as $pattern) {
        if (strpos($password, $pattern) !== false) {
            return false;
        }
    }
    foreach ($sequential_chars as $seq) {
        if (strpos($password, $seq) !== false || strpos($password, strrev($seq)) !== false) {
            return false;
        }
    }

    // Password passed all checks
    return true;
}

if (isset($_POST['go'])) {
    extract($_POST);
    
    // Check if all fields are filled
    if (!empty($full_names) && !empty($username) && !empty($password) && !empty($tel) && !empty($business_name) && !empty($business_type) && !empty($addresses)) {
        // If all fields are filled, proceed with registration
        
        // Validation for full names
        if (preg_match('/^[a-zA-Z ]{3,}$/', $full_names)) { // Minimum three names and only letters and spaces allowed
            
            // Email format validation
            if (validateEmail($username)) {
                
                // Password complexity and length validation
                if (validatePassword($password)) {
                    
                    // Validate business type
                    if ($business_type !== "Option 0") {
                        // Hash and encrypt password
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                        $user = new users();
                        $user->create_users($full_names, $username, $hashed_password, $tel, $business_name, $business_type, $addresses, $role);

                        echo "<div class='alert alert-success'>Registration successful. Please <a href='login.php'>click here to login</a>.</div>";
                    } else {
                        // If business type is not selected
                        echo "<div class='alert alert-danger'>Please select a business type.</div>";
                    }
                    
                } else {
                    // If password does not meet complexity or length requirements
                    echo "<div class='alert alert-danger'>Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character, and should not contain common patterns or sequential characters.</div>";
                }
                
            } else {
                // If email format is invalid
                echo "<div class='alert alert-danger'>Invalid email format.</div>";
            }
            
        } else {
            // If full names do not meet validation criteria
            echo "<div class='alert alert-danger'>Full names must contain at least three names and only letters and spaces are allowed.</div>";
        }
    } else {
        // If any field is empty, show error message
        echo "<div class='alert alert-danger'>All fields are required.</div>";
    }
}
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
                    <label for="business_type">Business Type</label>              
                    <select name="business_type" class="form-control" required>
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