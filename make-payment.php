<?php
include_once("head.php");

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "bpayment");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Process the form submission
if (isset($_POST['go'])) {
    // Validate form fields to ensure none are empty
    if (empty($_POST['business_name']) || empty($_POST['date']) || empty($_POST['Period']) || empty($_POST['Amount']) || empty($_POST['payment_method']) || empty($_POST['business_type']) || empty($_POST['phone_number']) || empty($_POST['email'])) {
        // Handle empty fields
        echo "<div class='alert alert-danger'>Please fill in all the fields.</div>";
    } else {
        extract($_POST);

        // Store the data in the payment_history table
        $query = "INSERT INTO payment_history (business_name, date, period, amount, payment_method, business_type, phone_number, email) 
                  VALUES ('$business_name', '$date', '$Period', '$Amount', '$payment_method', '$business_type','$phone_number', '$email')";

if (mysqli_query($conn, $query)) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sammuelryan4050@gmail.com'; // Replace with your Gmail email address
        $mail->Password = 'kbxp hwbl icxk btux'; // Replace with your Gmail password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('CRMS@gmail.com', 'CRMS');
        $mail->addAddress($email, 'User');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Payment Confirmation';
        $mail->Body = nl2br("Dear user \n\nThank you for initiating a payment process.\n\nBelow are your payment Details:\nBusiness Name: $business_name\nAmount Paid: KES $Amount\nCategory: $business_type\nDate: " . date('Y-m-d') . "\n\nBest regards,\nPayment Team, \nIn case of any issues with your payment kindly dial us through the Phone number: 0743248996");
        $mail->AltBody = "Dear $business_name,\n\nThank you for your payment.\n\nDetails of your payment:\nBusiness Name: $business_name\nAmount to be paid: KES $Amount\nCategory: $business_type \nPayment Method: KES $payment_method\nDate:  " . date('Y-m-d') . "\n\nBest regards,\nPayment Team";

        $mail->send();

        $confirmationMessage = "Do you wish to proceed with this payment?";
        echo "<script>alert('Payment processing email has been sent kindly check your email.');</script>"; // Moved here

        $confirmationMessage = "Do you wish to proceed with this payment?";
        echo "<script>
                if (confirm('$confirmationMessage')) {
                    window.location.href='" . ($payment_method === 'Mpesa' ? 'mpesa-page.php' : 'stripe.php') . "';
                } else {
                    window.location.href='home.php';
                }
              </script>";
    } catch (Exception $e) {
        echo "<script>alert('Error: Payment confirmation email could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
    }

    mysqli_close($conn);

    exit;
} else {
    echo "<script>alert('Error: Failed to add record to the database.');</script>";
}
}
}
?>

<div class="row">
    <div class="col-md-12">
        <h2>Revenue</h2>
        <h5>Make Payment</h5>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-12">
        <?php
        // Autofill the phone number and email fields with the logged-in user's information
        $loggedInUserPhone = $_SESSION['user_details']['phone_number'] ?? '';
        $loggedInUserEmail = $_SESSION['user_details']['email'] ?? '';

        if (isset($_POST['go'])) {
            extract($_POST);
            $p = new payment();
            $status = "Pending Approval";
            $p->create_payment($business_name, $date, $Period, $status, $Amount, $payment_method, $business_type);

            // Redirect based on payment method
            $confirmationMessage = "Do you wish to proceed with this payment?";
            // Display JavaScript confirmation dialog
            echo "<script>
                    if (confirm('$confirmationMessage')) {
                        window.location.href='" . ($payment_method === 'Mpesa' ? 'mpesa-page.php' : 'stripe.php') . "';
                    } else {
                        window.location.href='home.php';
                    }
                  </script>";

            // Execute the API here after payment creation
            $apiURL = "";
            $apiKey = "abc1232";
            $apiSecret = "were3qa";

            // Construct the API request parameters
            $apiParams = http_build_query([
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
                'service_id' => 'update-payment', // Specify the service ID for adding payment
                'business_name' => $business_name,
                'date' => $date,
                'period' => $Period,
                'status' => $status
                // Add more parameters as needed
            ]);

            // Execute the API request
            $apiResponse = file_get_contents($apiURL . '?' . $apiParams);
            $apiFeedback = json_decode($apiResponse, true);

            // Handle API feedback as needed
            // For example, you can log API response or display a message to the user
            if ($apiFeedback['status'] == 200) {
                echo "<script>alert('Payment was sent.'); window.location.href='make-payment.php';</script>";
            } else {
                echo "<script>alert('Error: Payment was not sent.');</script>";
            }
        }
        ?>

        <form method="POST" onsubmit="return validateForm()">
            <div class="row form-group">
                <div class="col-12 col-sm-4">Business Name</div>
                <div class="col-12 col-sm-9">
                    <input type="text" name="business_name" value="<?php echo $_SESSION['user_details']['business_name']; ?>" class="form-control" required>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Category</div>
                <div class="col-12 col-sm-9">
                    <select name="business_type" class="form-control" required onchange="updateAmount(this.value)">
                        <option value="Option 0">Choose your category</option>
                        <option value="Event Centers">Event Centers</option>
                        <option value="Fines and Penalties">Fines and Penalties</option>
                        <option value="Market Levy">Market Levy</option>
                        <option value="Security">Security</option>
                        <option value="Waste Management">Waste Management</option>
                        <option value="Fees and Permits">Fees and Permits</option>
                        <option value="Express Way">Express Way</option>
                    </select>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Date of Payment</div>
                <div class="col-12 col-sm-9">
                    <input type="date" name="date" class="form-control" required>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Period</div>
                <div class="col-12 col-sm-9">
                    <select name="Period" class="form-control" required>
                        <option value="">Select Period</option>
                        <option value="2023/2024">2023/2024</option>
                        <option value="2024/2025">2024/2025</option>
                        <option value="2025/2026">2025/2026</option>
                    </select>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Payment Method</div>
                <div class="col-12 col-sm-9">
                    <select name="payment_method" class="form-control" required>
                        <option value="">Select mode of payment</option>
                        <option value="Mpesa">Mpesa</option>
                        <option value="Credit Card">Credit</option>
                    </select>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Amount Paid (KES)</div>
                <div class="col-12 col-sm-9">
                    <input type="number" name="Amount" id="amount" class="form-control" required readonly>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Phone Number</div>
                <div class="col-12 col-sm-9">
                    <input type="tel" name="phone_number" value="<?php echo $_SESSION['user_details']['tel']; ?>" class="form-control" required readonly>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-12 col-sm-4">Email</div>
                <div class="col-12 col-sm-9">
                    <input type="email" name="email" value="<?php echo $_SESSION['user_details']['username']; ?>" class="form-control" required readonly>
                </div>
            </div>

            <button type="submit" name="go" class="btn btn-primary btn-lg btn-block">SUBMIT PAYMENT</button>
        </form>
    </div>
</div>

<script>
    function updateAmount(businessType) {
        var amountField = document.getElementById('amount');
        switch (businessType) {
            case 'Event Centers':
                amountField.value = '10000';
                break;
            case 'Fines and Penalties':
                amountField.value = '5000';
                break;
            case 'Market Levy':
                amountField.value = '3000';
                break;
            case 'Security':
                amountField.value = '2000';
                break;
            case 'Waste Management':
                amountField.value = '5000';
                break;
            case 'Fees and Permits':
                amountField.value = '12000';
                break;
            case 'Express Way':
                amountField.value = '6000';
                break;
        }
    }

    function validateForm() {
        var fields = ["business_name", "date", "Period", "Amount", "payment_method", "business_type", "phone_number", "email"];
        for (var i = 0; i < fields.length; i++) {
            var fieldValue = document.getElementsByName(fields[i])[0].value;
            if (fieldValue === "") {
                alert("Please fill in all the fields.");
                return false; // Stop form submission
            }
        }
        // If all fields are filled, proceed with the form submission
        return true;
    }
</script>
