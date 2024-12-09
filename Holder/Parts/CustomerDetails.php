<?php

namespace Holder\Parts;

use Holder\PartInterface;

class CustomerDetails implements PartInterface
{
    protected const KEY = 'customer_details';

    public ?string $name = null;
    public ?string $phone = null;
    public ?string $email = null;
    public ?string $country = null;
    public ?string $state = null;
    public ?string $city = null;
    public ?string $address = null;
    public ?string $ip = null;
    public ?string $zip = null;

    public function __construct(
        ?string $name = null,
        ?string $phone = null,
        ?string $email = null,
        ?string $country = null,
        ?string $state = null,
        ?string $city = null,
        ?string $address = null,
        ?string $ip = null,
        ?string $zip = null
    ) {
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->country = $country;
        $this->state = $state;
        $this->city = $city;
        $this->address = $address;
        $this->ip = $ip;
        $this->zip = $zip;
    }

    public function build(): array
    {
        return [
            static::KEY => [
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'country' => $this->country,
                'state' => $this->state,
                'city' => $this->city,
                'address' => $this->address,
                'ip' => $this->ip,
                'zip' => $this->zip,
            ]
        ];
    }
}
