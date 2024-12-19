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

// Test Payment Request
// include 'Tests/PaymentRequest.php';

// Test Query Token
// include 'Tests/TestTokenQuery.php';

// Test Query Delete
// include 'Tests/TestTokenDelete.php';

// Test Transaction Query
// include 'Tests/TransactionQuery.php';

// Test Refund
include 'Tests/RefundRequest.php';
