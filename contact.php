<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Email address where you want to receive the contact form submissions
    $to = 'sammysunryan@gmail.com'; // Replace with your email address

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sammuelryan4050@gmail.com'; // Replace with your Gmail email address
        $mail->Password = 'kbxp hwbl icxk btux'; // Replace with your Gmail password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('sammuelryan4050@gmail.com', 'CRMS Management');
        $mail->addAddress($email, $name); // Add client's email dynamically

        // Content
        $mail->isHTML(false);
        $mail->Subject = "Thank You for your response";
        $mail->Body = "You have received a new message from The County Revenue Management System.\n\n" .
            "Thank You $name for your response. We will address your concerns in coming weeks.\n" ;
           

        // Send email
        $mail->send();
        echo '<script>alert("Thank you for your message. We will get back to you shortly."); window.location.href = "index.php";</script>';
        exit(); // Prevent further execution
    } catch (Exception $e) {
        echo '<script>alert("Oops! Something went wrong. Please try again later.")</script>';
    }
}
?>
