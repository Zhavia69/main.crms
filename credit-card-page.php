<?php

require __DIR__ . "/vendor/autoload.php";

$stripe_secret_key = "sk_test_51PBDC0LyqYP6tDVQ2ML4fGC08sHEdjDuBkTmIpkPKStdjV9FCaEuzeSUiW1wkRTulyymhoAx0fpYkBViXIFdfuD9009Z6ngsVL";
\Stripe\Stripe::setApiKey($stripe_secret_key);

$amount = isset($_POST['amount']) ? $_POST['amount'] : 0;

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
