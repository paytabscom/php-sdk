<?php
 
use Paytabs\Sdk\Gateway\Endpoints\Uae;
use Paytabs\Sdk\Gateway\Gateway;
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\Paytabs;
 
require_once 'vendor/autoload.php';
 
$gateway = new Gateway(Uae::getInstance(), 47170, 'SRJNLKK2Z2-HWRGM6JDZM-MGMGGNW9JZ');
 
$return = array_key_exists('result', $_GET);
if ($return) {
    require_once 'index_ipn.php';
    exit;
}
 
$http = new Http();
$http->setLogger(Paytabs::getLogger());
 
//
 
$trxRef = 'TST2500302199207';
$urlCallback = 'https://ac05-5-193-61-186.ngrok-free.app?result=1';
$urlReturn = 'https://ac05-5-193-61-186.ngrok-free.app?result=1&mode=return';
$token = '2C4654BD67A3E830C6B693FA63827EB0';
$invoiceId = '4194534';
 
$returnUsingGet = false;
if ($returnUsingGet) {
    $urlReturn .= '&get=1';
}
 
// Test Payment Request
include 'Samples/PaymentRequest.php';
 
// Test Query Token
// include 'UseCases/TokenQuery.php';
 
// Test Query Delete
// include 'UseCases/TokenDelete.php';
 
// Test Transaction Query
// include 'UseCases/TransactionQuery.php';
 
// Test Refund
// include 'UseCases/RefundRequest.php';
 
// Invoice New
// include 'UseCases/InvoiceNew.php';
 
// include 'UseCases/ResultBrowser.php';
// include 'UseCases/ResultCallback.php';
 
// InvoiceStatus GET
// include 'UseCases/InvoiceStatusGet.php';
// POST
// include 'UseCases/InvoiceStatus.php';
 
// Payment methods:
// include 'UseCases/PaymentMethods.php';
 