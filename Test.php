<?php

use Enums\TranClass;
use Enums\TranType;
use Gateway\Endpoints\Uae;
use Gateway\Gateway;
use Holder\Builders\Basic;
use Holder\Builders\HostedPage;
use Holder\Parts\CustomerDetails;
use Holder\Parts\Framed;
use Holder\Parts\ShippingDetails;
use Http\Http;
use Request\PaymentRequest;
use Request\Request;

require_once 'Holder/BuilderInterface.php';
require_once 'Holder/PartInterface.php';
require_once 'Holder/PayloadInterface.php';

require_once 'Holder/Payload/AbstractPayload.php';
require_once 'Holder/Payload/PaytabsPayload.php';

require_once 'Holder/Builders/AbstractHolder.php';
require_once 'Holder/Builders/Root.php';
require_once 'Holder/Builders/AirlineData.php';
require_once 'Holder/Builders/Followup.php';
require_once 'Holder/Builders/PrimaryPayment.php';
require_once 'Holder/Builders/HostedPage.php';

require_once 'Holder/Parts/Airline.php';
require_once 'Holder/Parts/ApplePayToken.php';
require_once 'Holder/Parts/Cart.php';
require_once 'Holder/Parts/CustomerDetails.php';
require_once 'Holder/Parts/Framed.php';
require_once 'Holder/Parts/HideShipping.php';
require_once 'Holder/Parts/PaypageLang.php';
require_once 'Holder/Parts/PluginInfo.php';
require_once 'Holder/Parts/ShippingDetails.php';
require_once 'Holder/Parts/Tokenise.php';
require_once 'Holder/Parts/TokeniseEnhanced.php';
require_once 'Holder/Parts/Transaction.php';
require_once 'Holder/Parts/TransactionRef.php';
require_once 'Holder/Parts/Urls.php';

require_once 'Gateway/Gateway.php';
require_once 'Gateway/Endpoint.php';
require_once 'Gateway/Endpoints/Uae.php';

require_once 'Enums/TranClass.php';
require_once 'Enums/TranType.php';
require_once 'Enums/TokenType.php';
require_once 'Enums/FramedTarget.php';
require_once 'Enums/HttpRequestPart.php';
require_once 'Enums/TokenPaymentFrequency.php';

require_once 'Http/Http.php';

require_once 'Logger/LoggerInterface.php';

require_once 'Request/Request.php';
require_once 'Request/PaymentRequest.php';

require_once 'Response/Response.php';
require_once '_Log.php';

//

$log = new Log();

$gateway = new Gateway(new Uae, 47170, 'SRJNLKK2Z2-HWRGM6JDZM-MGMGGNW9JZ');

$holder = new HostedPage();
$holder
    ->setCart("c01", "AED", 100.5, "Test")
    ->setTransaction(TranType::Sale, TranClass::Ecom)
    ->setPluginInfo('PHP', phpversion(), '')
    ->setCustomerDetails(
        new CustomerDetails('Wajih', '0522222222', 'wajih@mail.com', 'ARE', 'Dubai', 'Dubai', null, '1.1.1.1', '11111')
    )
    ->setShippingDetails(
        new ShippingDetails('Wajih 2')
    )
    ->setHideShipping(false)
;

$request = new PaymentRequest($gateway, $holder);

$http = new Http();
$http->setLogger($log);
$http->setRequest($request);

$response = $http->submit();

var_dump($holder);
var_dump($response);
