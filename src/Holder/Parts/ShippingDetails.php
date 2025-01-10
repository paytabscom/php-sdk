<?php

namespace Holder\Parts;

use Holder\PartInterface;

class ShippingDetails extends CustomerDetails implements PartInterface
{
    protected const KEY = 'shipping_details';
}
