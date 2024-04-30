<?php
// Include necessary files
include_once('autoload.php');
include_once('head.php');

// Check if payment ID is provided
if(isset($_GET['id'])) {
    // Get the payment ID from the URL
    $payment_id = $_GET['id'];

    // Retrieve the payment details based on the provided ID
    $payment = new Payment($payment_id);

    // Check if the payment exists
    if($payment->exists()) {
        // Approve the payment
        $payment->approve();

        // Redirect back to the all-payment.php page after approval
        header('Location: all-payment.php');
        exit;
    } else {
        // Redirect back to the all-payment.php page with an error message if the payment does not exist
        header('Location: all-payment.php?error=Payment not found');
        exit;
    }
} else {
    // Redirect back to the all-payment.php page with an error message if payment ID is not provided
    header('Location: all-payment.php?error=Payment ID not provided');
    exit;
}
?>

<?php include_once('foot.php'); ?>
<?php include("ffoot.php"); ?>

