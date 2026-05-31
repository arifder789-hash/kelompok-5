<?php
require_once 'c:/xampp/htdocs/ramezafarmnew/config/db.php';
require_once 'c:/xampp/htdocs/ramezafarmnew/midtrans/Midtrans.php';

\Midtrans\Config::$serverKey = 'Mid-server-d267yyo9s4V5oDLRXQcE78c2';
\Midtrans\Config::$clientKey = 'Mid-client-HkgOOneCrvc9U39a';
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// simulasi dari database untuk 'ORD 20260529 902'
$transaction = array(
    'transaction_details' => array(
        'order_id' => 'ORD 20260529 902',
        'gross_amount' => 35000,
    ),
    'customer_details' => array(
        'first_name' => 'Arip',
        'phone'      => '628123456789',
    )
);

try {
    $snapToken = \Midtrans\Snap::getSnapToken($transaction);
    echo "TOKEN BERHASIL DIBUAT: " . $snapToken;
} catch (Exception $e) {
    echo "ERROR MIDTRANS: " . $e->getMessage();
}
