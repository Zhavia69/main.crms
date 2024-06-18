<?php

require __DIR__ . "/vendor/autoload.php";

$stripe_secret_key = "sk_test_51PBDC0LyqYP6tDVQ2ML4fGC08sHEdjDuBkTmIpkPKStdjV9FCaEuzeSUiW1wkRTulyymhoAx0fpYkBViXIFdfuD9009Z6ngsVL";
\Stripe\Stripe::setApiKey($stripe_secret_key);

// Check if the amount is set and not empty
if (!isset($_POST['amount']) || $_POST['amount'] === '') {
    // If the amount is blank, display an error message and stop further execution
    echo "<script>alert('Error: Amount cannot be blank.'); window.location.href = 'http://localhost/MAIN/home.php';</script>";
    exit;
}

$amount = $_POST['amount'];

$line_items = [
    [
        "quantity" => 1,
        "price_data" => [
            "currency" => "kes",
            "unit_amount" => $amount * 100, // Convert amount to cents
            "product_data" => [
                "name" => "Custom Product"
            ]
        ]
    ]
];

$checkout_session = \Stripe\Checkout\Session::create([
    "mode" => "payment",
    "success_url" => "http://localhost/MAIN/success.php",
    "cancel_url" => "http://localhost/MAIN/home.php",
    "locale" => "auto",
    "line_items" => $line_items
]);

header("Location: " . $checkout_session->url);
