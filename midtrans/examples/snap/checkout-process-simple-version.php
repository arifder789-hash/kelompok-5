<?php
// This is just for very basic implementation reference, in production, you should validate the incoming requests and implement your backend more securely.
// Please refer to this docs for snap popup:
// https://docs.midtrans.com/en/snap/integration-guide?id=integration-steps-overview

namespace Midtrans;

require_once dirname(__FILE__) . '/../../Midtrans.php';
// Set Your server key
// can find in Merchant Portal -> Settings -> Access keys
Config::$serverKey = 'Mid-server-d267yyo9s4V5oDLRXQcE78c2';
Config::$clientKey = 'Mid-client-HkgOOneCrvc9U39a';

// non-relevant function only used for demo/example purpose
printExampleWarningMessage();

// Uncomment for production environment
// Config::$isProduction = true;
Config::$isSanitized = Config::$is3ds = true;

// Required

include "../../../config/db.php";
$order_id = $_GET['order_id'];

// Query untuk menampilkan data pesanan dan pelanggan
$query = "SELECT p.*, pel.email FROM pesanan p LEFT JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan WHERE p.kode_pesanan = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$order_id]);
$data = $stmt->fetch(\PDO::FETCH_ASSOC);

if (!$data) {
    die("Pesanan tidak ditemukan.");
}

$nama = $data['nama_penerima'];
$email = $data['email'] ? $data['email'] : 'noemail@ramezafarm.com';
$biaya = (int) $data['grand_total'];
$phone = $data['no_wa'];

$transaction_details = array(
    'order_id' => $order_id,
    'gross_amount' => $biaya, // no decimal allowed for creditcard
);

// Optional
$item_details = array (
    array(
        'id' => 'pesanan-'.$order_id,
        'price' => $biaya,
        'quantity' => 1,
        'name' => "Pembayaran Pesanan"
    ),
);

// Optional
$customer_details = array(
    'first_name'    => $nama,
    'last_name'     => "",
    'email'         => $email,
    'phone'         => $phone,
);

// Fill transaction details
$transaction = array(
    'transaction_details' => $transaction_details,
    'customer_details' => $customer_details,
    'item_details' => $item_details,
);

$snap_token = '';
try {
    $snap_token = Snap::getSnapToken($transaction);
}
catch (\Exception $e) {
    echo $e->getMessage();
}


function printExampleWarningMessage() {
    if (strpos(Config::$serverKey, 'your ') != false ) {
        echo "<code>";
        echo "<h4>Please set your server key from sandbox</h4>";
        echo "In file: " . __FILE__;
        echo "<br>";
        echo "<br>";
        echo htmlspecialchars('Config::$serverKey = \'Mid-server-d267yyo9s4V5oDLRXQcE78c2\';');
        die();
    } 
}

?>

<!DOCTYPE html>
<html>
    <body>
        <button id="pay-button">Pay!</button>
        <!-- TODO: Remove ".sandbox" from script src URL for production environment. Also input your client key in "data-client-key" -->
     <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo Config::$clientKey;?>"></script>
        <script type="text/javascript">
            document.getElementById('pay-button').onclick = function(){
                // SnapToken acquired from previous step
                snap.pay('<?php echo $snap_token?>');
            };
        </script>
    </body>
</html>
