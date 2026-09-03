# Payment Request: Hosted Payment Page
- Version: `1.1.0`
- SDK version: >= `4.0.0`

Here is a basic example of how to use the PayTabs SDK:

1. Prepare the Authentication (Profile):
```php
use Paytabs\Sdk\Profile\ProfilesFactory;

$profileId = 123; // your profile id
$serverKey = 'SRxxxx-xxxx-xxx'; // the server key
$profile = ProfilesFactory::createUaeProfile($profileId, $serverKey);
```

2. Build the Payload:

```php
use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\Payload\Parts\CustomerDetails;

$hostedPage = PayloadsFactory::createHostedPage();
$hostedPage
    ->buildTransaction(TranType::Sale, TranClass::Ecom)
    ->buildCart('order-01', 'AED', 100, 'Order 01 description')
    ->buildCustomerDetails(
        (new CustomerDetails('John Doe', null, 'john@email-domain.com'))
            ->setAddress('ARE', 'Dubai', 'Dubai', 'nsr st')
    )
    ->buildHideShipping(true);
```

3. Create the Request:
```php
use Paytabs\Sdk\Request\RequestsFactory;

$paymentRequest = RequestsFactory::createPaymentRequest($hostedPage, $profile);
```

4. Create the Http connector:
```php
use Paytabs\Sdk\Http\Http;
use Paytabs\Sdk\PaytabsLogger;

$http = new Http();
$http
    ->setLogger(PaytabsLogger::getInstance()->logger)
    ->setRequest($paymentRequest);

try {
    $response = $http->submit();
} catch (\Paytabs\Sdk\Exceptions\HttpRequestException $e) {
    // Raised for transport failures (DNS, TLS, timeout), and for a non-2xx
    // response whose body is empty or is not JSON — a CDN or WAF error page,
    // for example. A non-2xx response *with* a JSON body is not thrown: it is
    // returned for response-layer mapping and surfaces via isFailure().
    echo $e->getMessage();
    exit;
}
```

To catch anything the SDK itself raises, catch the shared interface instead:

```php
use Paytabs\Sdk\Exceptions\PaytabsExceptionInterface;

try {
    $response = $http->submit();
} catch (PaytabsExceptionInterface $e) {
    // Any SDK exception: transport, configuration, signature, mapping.
}
```

> **Do not blindly retry a payment request.**
> PayTabs has no idempotency-key header, and `cart_id` is your own reference
> which the gateway does not enforce as unique — so a retry after a timeout can
> create a **second charge**. The SDK therefore performs no automatic retries.
> If a request times out, do not resend it: query the transaction first (see
> `Samples/TransactionQuery.php`) and resend only if no transaction exists.

5. Response handle:
Response may have 3 formats:

* Failure:
    If the requests declined, reasons: Authentication error, Parameters error ...
    ```php
    if ($response->isFailure()) {
        // Map the response to Failure class, So it's easier to deal with it
        // Paytabs\Sdk\Response\Payload\Payloads\Failure
        $failure = $response->getFailure();
        echo $failure->code;
        echo $failure->message;

        exit;
    }
    ```
* Redirect: If the Payment Gateway returned a redirect URL.
    ```php
    if ($response->isRedirect()) {
        // Map the response to Redirect class
        // Paytabs\Sdk\Response\Payload\Payloads\Redirect
        $redirect = $response->getRedirect();
        echo $redirect->redirect_url;

        exit;
    }
    ```
* Completed: if it is a completed successful response
    Response has a Payload object which is the data returned from the Server.
    ```php
    $completed = $response->getPayloadMapped();
    ```
