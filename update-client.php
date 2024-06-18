<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit;
}

// Include necessary files
include_once 'autoload.php';

// Get user details from the session
$user_id = $_SESSION['user_id'];
$full_names = $_SESSION['user_details']['full_names'];
$username = $_SESSION['user_details']['username'];
$tel = $_SESSION['user_details']['tel'];
$business_name = $_SESSION['user_details']['business_name'];
$type_of_business = $_SESSION['user_details']['type_of_business'];
$addresses = $_SESSION['user_details']['addresses'];

// Initialize message variable
$message = '';

// Handle form submission for updating user details
if (isset($_POST['update'])) {
    // Extract form data
    extract($_POST);

    // Update user details in the database
    $user = new users();
    $result = $user->update_users($user_id, $full_names, $tel, $business_name, $type_of_business, $addresses, $ID_Number, ''); // Pass ID number to update method

    if ($result) {
        // Update session with new user details
        $_SESSION['user_details']['full_names'] = $full_names;
        $_SESSION['user_details']['tel'] = $tel;
        $_SESSION['user_details']['business_name'] = $business_name;
        $_SESSION['user_details']['addresses'] = $addresses;
        $_SESSION['user_details']['ID_Number'] = $ID_Number; // Update ID number in session

        // Set success message
        $message = 'Profile updated successfully.';
        
        // Redirect to the dashboard home page
        header('location: home.php');
        exit;
    } else {
        // Set error message
        $message = 'Error updating profile. Please try again.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
   
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background: #F3F3F3; /* Apply background color from CSS */
            padding: 15px; /* Apply padding from CSS */
        }
        h1 {
            text-align: center;
            color: #2EA7EB; /* Apply color from CSS */
            margin-bottom: 30px; /* Add margin for spacing */
        }
        .container {
            background: #fff; /* Apply background color from CSS */
            padding: 20px; /* Apply padding from CSS */
            border-radius: 5px; /* Add border radius for rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add shadow for depth */
        }
        .form-group {
            margin-bottom: 20px; /* Add margin for spacing */
        }
        label {
            font-weight: 600; /* Apply font weight from CSS */
            color: #202020; /* Apply color from CSS */
        }
        input[type="email"],
        input[type="text"] {
            width: 100%; /* Make input fields full width */
            padding: 10px; /* Apply padding from CSS */
            border: 1px solid #ccc; /* Apply border from CSS */
            border-radius: 5px; /* Add border radius for rounded corners */
        }
        .btn-primary {
            background: #2EA7EB; /* Apply background color from CSS */
            border: none; /* Remove default button border */
            border-radius: 5px; /* Add border radius for rounded corners */
            padding: 10px 20px; /* Apply padding from CSS */
            color: #fff; /* Apply color from CSS */
            cursor: pointer; /* Change cursor to pointer on hover */
        }
        .btn-primary:hover {
            background: #009B50; /* Change background color on hover */
        }
        .alert {
            margin-bottom: 20px; /* Add margin for spacing */
            padding: 15px; /* Apply padding from CSS */
            border-radius: 5px; /* Add border radius for rounded corners */
        }
        .alert-success {
            background: #00CE6F; /* Apply success background color from CSS */
            color: #fff; /* Apply color from CSS */
        }
        .alert-danger {
            background: #DB0630; /* Apply danger background color from CSS */
            color: #fff; /* Apply color from CSS */
        }
    </style>
    <script>
        // JavaScript function to show success message and redirect after a delay
        function showNotificationAndRedirect() {
            var messageDiv = document.getElementById('message');
            messageDiv.innerHTML = '<?php echo $message; ?>'; // Display the message
            messageDiv.style.display = 'block'; // Display the message

            // Redirect after 3 seconds
            setTimeout(function(){
                window.location.href = 'home.php';
            }, 3000); // 3000 milliseconds = 3 seconds
        }
    </script>

</head>
<body>
<div class="container">
    <h1>Update Profile</h1>
    <?php if (!empty($message)) : ?>
        <div class="alert <?php echo ($result) ? 'alert-success' : 'alert-danger'; ?>" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <form method="POST">

        <div class="form-group">
            <label for="username">Email</label>
            <input type="email" class="form-control" id="username" name="username" value="<?php echo $username; ?>" disabled>
        </div>
        
        <div class="form-group">
            <label for="full_names">Full Names</label>
            <input type="text" class="form-control" id="full_names" name="full_names" value="<?php echo $full_names; ?>" required>
        </div>

        <div class="form-group">
            <label for="tel">Phone</label>
            <input type="text" class="form-control" id="tel" name="tel" value="<?php echo $tel; ?>" required>
        </div>

        <div class="form-group">
            <label for="business_name">Business Name</label>
            <input type="text" class="form-control" id="business_name" name="business_name" value="<?php echo $business_name; ?>" required>
        </div>

        <div class="form-group">
            <label for="addresses">Address</label>
            <input type="text" class="form-control" id="addresses" name="addresses" value="<?php echo $addresses; ?>" required>
        </div>

        <button type="submit" name="update" class="btn btn-primary">Update Profile</button>
    </form>
</div>

</body>
</html>
