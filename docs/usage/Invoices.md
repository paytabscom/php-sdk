# Invoices
- Version: `1.0.0`
- SDK version: `1.0.0`

## Create new Invoice

1. Prepare the Authentication (Profile):
    ```php
    use Paytabs\Sdk\Profile\Profile;
    use Paytabs\Sdk\Profile\Endpoints\Uae;

    $profileId = 123; // your profile id
    $serverKey = 'SRxxxx-xxxx-xxx' // the server key
    $profile = new Profile(Uae::getInstance(), $profileId, $serverKey);
    ```

2. Prepare the Line items:
    ```php
    use Paytabs\Sdk\Request\Payload\Parts\Partials\Invoice\LineItem;
    use Paytabs\Sdk\Request\Payload\Parts\Partials\Invoice\LineItems;
    
    $item1 = LineItem::init()
        ->setTitle('sku', 'desc', 'https://test.com')
        ->setPrice(1, 10, 10);

    $item2 = LineItem::init()
        ->setTitle('item-02')
        ->setPrice(1, 10, 10);

    $lineItems = new LineItems($item1, $item2);
    ```

3. Prepare the Invoice part:
    ```php
    use Paytabs\Sdk\Request\Payload\Parts\Invoice as InvoicePart;

    $invoicePart = new InvoicePart();
    $invoicePart
        ->setDates(null, null, '2026-01-27T13:33:00+04:00')
        ->setLineItems($lineItems);
    ```

4. Create the invoice Payload:
    ```php
    use Paytabs\Sdk\Request\Payload\PayloadsFactory;

    $holder = PayloadsFactory::invoiceCreate();
    $holder
        ->buildTransaction(TranType::Sale, TranClass::Ecom)
        ->buildInvoice($invoicePart)
        ->buildCart('inv-01', 'AED', 20, 'Invoice test');
    ```

5. Create the Invoice Request:
    ```php
    use Paytabs\Sdk\Request\RequestsFactory;

    $request = RequestsFactory::invoiceNew($profile, $holder);
    ```

6. Create the Http connector:
    ```php
    use Paytabs\Sdk\Http\Http;

    $http = new Http();
    $http->setRequest($request);
    $response = $http->submit();
    ```

7. Map the response's payload:
    ```php
    // Paytabs\Sdk\Response\Payload\Payloads\Invoice\NewInvoice
    $invoice = $response->getPayloadMapped();
    echo $invoice->invoice_id;
    echo $invoice->invoice_link;
    ```

---
## Invoice Status check

1. Prepare the Authentication (Profile) (Explained before).

2. Prepare the Invoice status payload:
    ```php
    $invoiceId = 12345
    $holder2 = PayloadsFactory::invoiceStatus();
    $holder2->buildInvoiceId($invoiceId);
    ```

3. Create the Invoice status request:
    ```php
    $request2 = RequestsFactory::invoiceStatus($profile, $holder2);
    ```

4. Submit the request:
    ```php
    $http->setRequest($request2);
    $response2 = $http->submit();
    ```

5. Map  the response:
    ```php
    // Paytabs\Sdk\Response\Payload\Payloads\Invoice\InvoiceStatus
    $invoiceStatus = $response->getPayloadMapped();

    echo $invoiceStatus->invoice_status;
    ```
