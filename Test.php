<?php

use Gateway\Endpoints\Uae;
use Gateway\Gateway;
use Http\Http;

require_once 'Test_req.php';

//

$log = Log::getInstance();

$gateway = new Gateway(Uae::getInstance(), 47170, 'SRJNLKK2Z2-HWRGM6JDZM-MGMGGNW9JZ');

$http = new Http();
$http->setLogger($log);

//

$trxRef = 'TST2500302199207';
$urlCallback = 'https://webhook.site/1c481b22-9981-4372-85cc-c79bb0e342cc';
$token = '2C4654BD67A3E830C6B693FA63827EB0';

echo '<pre>';

// Test Payment Request
include 'Tests/PaymentRequest.php';

// Test Query Token
// include 'Tests/TokenQuery.php';

// Test Query Delete
// include 'Tests/TokenDelete.php';

// Test Transaction Query
// include 'Tests/TransactionQuery.php';

// Test Refund
// include 'Tests/RefundRequest.php';
