<?php

// APP_ROOT points to the root directory of the library
define("APP_ROOT", realpath(dirname(__FILE__)) . '/../');

use Paytabs\Sdk\Gateway\Endpoints\Egypt;
use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;

require_once APP_ROOT . 'vendor/autoload.php';

$configs = parse_ini_file(APP_ROOT . 'Samples/config.ini');
$gateway = new Gateway(Egypt::getInstance(), $configs['profile_id'], $configs['server_key']);

$return = array_key_exists('result', $_GET);
if ($return) {
    require_once 'index_ipn.php';
    exit;
}

$http = new Http();
$http->setLogger(Paytabs::getLogger());

//

$trxRef = $configs['trx_ref'];

$urlBase = $configs['base_url'];
$urlCallback = $urlBase . '?result=1';
$urlReturn = $urlBase . '?result=1&mode=return';

$token = $configs['token'];

$invoiceId = $configs['invoice_id'];

$returnUsingGet = false;
if ($returnUsingGet) {
    $urlReturn .= '&get=1';
}

// Test Payment Request
include APP_ROOT . 'Samples/PaymentRequest.php';

// Test Query Token
// include APP_ROOT . 'Samples/TokenQuery.php';
// Test Query Delete
// include APP_ROOT . 'Samples/TokenDelete.php';

// Test Transaction Query
// include APP_ROOT . 'Samples/TransactionQuery.php';

// Test Refund
// include APP_ROOT . 'Samples/RefundRequest.php';

// include APP_ROOT . 'Samples/ResultBrowser.php';
// include APP_ROOT . 'Samples/ResultCallback.php';

// Invoice New
// include APP_ROOT . 'Samples/InvoiceNew.php';

// InvoiceStatus GET
// include APP_ROOT . 'Samples/InvoiceStatusGet.php';
// POST
// include APP_ROOT . 'Samples/InvoiceStatus.php';

// Payment methods:
// include APP_ROOT . 'Samples/PaymentMethods.php';
