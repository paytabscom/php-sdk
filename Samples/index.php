<?php

// APP_ROOT points to the root directory of the library
define('APP_ROOT', realpath(dirname(__FILE__)).'/../');

use Paytabs\Sdk\Enums\InvoicePaidPayMethods;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\Profile\Profile;

require_once APP_ROOT.'vendor/autoload.php';

include_once APP_ROOT.'Samples/config.php';
$configs = readConfigs();

$profile = new Profile($configs['endpoint'], $configs['profile_id'], $configs['server_key']);

$return = array_key_exists('result', $_GET);
if ($return) {
    require_once 'index_ipn.php';

    exit;
}

$http = new Http();
$http->setLogger(Paytabs::getLogger());

$trxRef = $configs['trx_ref'];

$urlBase = $configs['base_url'];
$urlCallback = $urlBase.'?result=1';
$urlReturn = $urlBase.'?result=1&mode=return';

$token = $configs['token'];
$token_enhanced = $configs['token_enhanced'];

$invoiceId = $configs['invoice_id'];
$invoiceCurrency = "SAR";
$invoiceTotal =  300.00;
$payMethod = InvoicePaidPayMethods::bank;
$payDescription = "test description";


$returnUsingGet = false;
if ($returnUsingGet) {
    $urlReturn .= '&get=1';
}

$samples = [
    1 => [
        'Payment Request',
        APP_ROOT.'Samples/PaymentRequest.php',
    ],
    2 => [
        'Own Form',
        APP_ROOT.'Samples/OwnForm.php',
    ],
    3 => [
        'Recurring Payment',
        APP_ROOT.'Samples/RecurringRequest.php',
    ],
    4 => [
        'Managed Form',
        APP_ROOT.'Samples/ManagedForm.php',
    ],
    10 => [
        'Query Token',
        APP_ROOT.'Samples/TokenQuery.php',
    ],
    11 => [
        'Token Delete',
        APP_ROOT.'Samples/TokenDelete.php',
    ],
    12 => [
        'Transaction Query',
        APP_ROOT.'Samples/TransactionQuery.php',
    ],
    20 => [
        'Refund',
        APP_ROOT.'Samples/RefundRequest.php',
    ],
    30 => [
        'Result Browser',
        APP_ROOT.'Samples/ResultBrowser.php',
    ],
    31 => [
        'Result CallBack',
        APP_ROOT.'Samples/ResultCallback.php',
    ],
    40 => [
        'Invoice New',
        APP_ROOT.'Samples/InvoiceNew.php',
    ],
    41 => [
        'Invoice Status GET',
        APP_ROOT.'Samples/InvoiceStatusGet.php',
    ],
    42 => [
        'Invoice Status POST',
        APP_ROOT.'Samples/InvoiceStatus.php',
    ],
    43 => [
        'Invoice Cancel',
        APP_ROOT.'Samples/InvoiceCancel.php',
    ],
    45 => [
        'Invoice Mark Paid',
        APP_ROOT.'Samples/InvoiceMarkPaid.php',
    ],
    50 => [
        'Payment Methods',
        APP_ROOT.'Samples/PaymentMethods.php',
    ],
];

$sampleId = filter_input(INPUT_GET, 'sample', FILTER_VALIDATE_INT);
if ($sampleId) {
    echo '<a href="?">Back</a><br>';
    echo '<h2>'.$samples[$sampleId][0].'</h2><br>';

    include $samples[$sampleId][1];

    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP SDK | Samples</title>
</head>

<body>
    <h1>PHP SDK Samples</h1>
    <div style="padding: 30px;">
        <ol>
            <?php foreach ($samples as $id => $sample) { ?>
                <li style="padding-bottom: 5px;">
                    <a href="?sample=<?php echo $id; ?>"><?php echo $sample[0]; ?></a>
                </li>
            <?php } ?>
        </ol>
    </div>
</body>

</html>