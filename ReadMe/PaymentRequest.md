# Payment Request: Hosted Payment Page

Here is a basic example of how to use the PayTabs SDK:

1. Prepare the Authentication (Profile):
```php
use Paytabs\Sdk\Profile\Profile;
use Paytabs\Sdk\Profile\Endpoints\Uae;

$profileId = 123; // your profile id
$serverKey = 'SRxxxx-xxxx-xxx' // the server key
$profile = new Profile(Uae::getInstance(), $profileId, $serverKey);
```

2. Build the Payload:

```php
use Paytabs\Sdk\Enums\TranClass;
use Paytabs\Sdk\Enums\TranType;
use Paytabs\Sdk\Request\Payload\PayloadsFactory;
use Paytabs\Sdk\Request\Payload\Parts\CustomerDetails;

$hostedPage = PayloadsFactory::hostedPage();
$hostedPage
    ->buildTransaction(TranType::Sale, TranClass::Ecom)
    ->buildCart('order-01', 'AED', 100, 'Oder 01 description')
    ->buildCustomerDetails(
        (new CustomerDetails('John Doe', null, 'john@email-domain.com'))
            ->setAddress('ARE', 'Dubai', 'Dubai', 'nsr st')
    )
    ->buildHideShipping(true);
```

3. Create the Request:
```php
use Paytabs\Sdk\Request\RequestsFactory;

$paymentRequest = RequestsFactory::paymentRequest($profile, $hostedPage);
```

4. Create the Http connector:
```php
use Paytabs\Sdk\Http\Http;

$http = new Http();
$http->setRequest($paymentRequest);
$response = $http->submit();
```

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
* Redirect: If the Payment gateway returned a redirect URL.
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
    $response->getPayload();
    ```
