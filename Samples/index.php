<?php

// APP_ROOT points to the root directory of the library
define('APP_ROOT', realpath(__DIR__).'/../');

use Paytabs\Sdk\Paytabs;
use Paytabs\Sdk\PaytabsLogger;
use Paytabs\Sdk\Profile\EndpointsFactory;
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Profile\ProfilesFactory;

require_once APP_ROOT.'vendor/autoload.php';

include_once APP_ROOT.'Samples/config.php';

$_endpoint = getConfig('ENDPOINT');
$_profileId = (int) getConfig('PROFILE_ID');
$_serverKey = getConfig('SERVER_KEY');
$_currency = getConfig('CURRENCY');
$_paymentToken = getConfig('PAYMENT_TOKEN');
$_themeId = (int) getConfig('THEME_ID');
$_token = getConfig('TOKEN');
$_tokenEnhanced = getConfig('TOKEN_ENHANCED');

// Create a profile instance using the ProfilesFactory and the EndpointsFactory
$profile = ProfilesFactory::createProfile(EndpointsFactory::getJordanEndpoint(), $_profileId, $_serverKey);
// Or you can create a profile instance directly using the Profile Factory
$profile = ProfilesFactory::createUaeProfile($_profileId, $_serverKey);
// Or you can create a profile instance directly using the Profile class
$profile = new Profile($_endpoint, $_profileId, $_serverKey);

$paytabs = Paytabs::getInstance($profile);
$ptLogger = PaytabsLogger::getInstance(null, true);
$logger = $ptLogger->logger;

$paytabs->setLogger($logger);

$return = array_key_exists('result', $_GET);
if ($return) {
    require_once 'IndexIpn.php';

    exit;
}

$trxRef = getConfig('TRANSACTION_REF');

$urlBase = getConfig('APP_URL');
$urlCallback = $urlBase.'?result=1';
$urlReturn = $urlBase.'?result=1&mode=return';

$_token = getConfig('TOKEN');
$_tokenEnhanced = getConfig('TOKEN_ENHANCED');

$invoiceId = (int) getConfig('INVOICE_ID');
$phoneNumber = getConfig('PHONE_NUMBER');

$returnUsingGet = false;
if ($returnUsingGet) {
    $urlReturn .= '&get=1';
}

$samples = [
    1001 => [
        'Payment Request Samples',
    ],
    1 => [
        'Payment Request',
        APP_ROOT.'Samples/PaymentRequest.php',
    ],
    5 => [
        'Payment Request (Basic)',
        APP_ROOT.'Samples/PaymentRequestSimple.php',
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
    1010 => [
        'Query',
    ],
    10 => [
        'Token Query',
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
    1020 => [
        'Follow Up',
    ],
    20 => [
        'Refund',
        APP_ROOT.'Samples/RefundRequest.php',
    ],
    1030 => [
        'Result Handling',
    ],
    30 => [
        'Result Browser',
        APP_ROOT.'Samples/ResultBrowser.php',
    ],
    31 => [
        'Result Callback',
        APP_ROOT.'Samples/ResultCallback.php',
    ],
    1040 => [
        'Invoices',
    ],
    40 => [
        'New Invoice',
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
    44 => [
        'Invoice Send SMS',
        APP_ROOT.'Samples/InvoiceSms.php',
    ],
    45 => [
        'Invoice Mark as Paid',
        APP_ROOT.'Samples/InvoiceMarkPaid.php',
    ],
    1050 => [
        'Factory Samples',
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

    include_once $samples[$sampleId][1];

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
    <div>
        <div>
            <label>
                Invoice ID:
                <input type="text" name="invoice_id" value="<?php echo $invoiceId; ?>" readonly>
            </label>
        </div>
        <div>
            <label>
                Transaction Reference:
                <input type="text" name="trx_ref" value="<?php echo $trxRef; ?>" readonly>
            </label>
        </div>
        <div>
            <label>
                Token:
                <input type="text" name="token" value="<?php echo $_token; ?>" readonly>
            </label>
        </div>
        <div>
            <label>
                Return URL:
                <input type="text" name="return_url" value="<?php echo $urlReturn; ?>" readonly size="50">
            </label>
            <br>
            <label>
                Callback URL:
                <input type="text" name="callback_url" value="<?php echo $urlCallback; ?>" readonly size="50">
            </label>
        </div>
        <hr>
        <div>
            <label>
                Redirect URL:
                <input type="text" name="redirect_url" value="<?php echo $urlRedirect ?? ''; ?>" readonly>
            </label>
        </div>
    </div>
    <div style="padding: 30px;">
        <ol>
            <?php foreach ($samples as $id => $sample) { ?>
                <?php if ($id > 1000) { ?>
                    <h3>
                        <?php echo $sample[0]; ?>
                    </h3>
                <?php continue;
                } ?>
                <li style="padding-bottom: 5px;">
                    <a href="?sample=<?php echo $id; ?>"><?php echo $sample[0]; ?></a>
                </li>
            <?php } ?>
        </ol>
    </div>
</body>

</html>
