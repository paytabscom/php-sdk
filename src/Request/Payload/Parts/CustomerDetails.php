<?php

namespace Paytabs\Sdk\Request\Payload\Parts;

class CustomerDetails extends AbstractPart
{
    protected const KEY = 'customer_details';

    public ?string $name = null;
    public ?string $phone = null;
    public ?string $email = null;

    public ?string $country = null;
    public ?string $state = null;
    public ?string $city = null;
    public ?string $street = null;
    public ?string $zip = null;

    public ?string $ip = null;

    public function __construct(
        ?string $name = null,
        ?string $phone = null,
        ?string $email = null
    ) {
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;

        return $this;
    }

    public static function init(
        ?string $name = null,
        ?string $phone = null,
        ?string $email = null
    ): self {
        return new static($name, $phone, $email);
    }

    public function copyFrom(
        self|ShippingDetails $details
    ): self {
        $this->name = $details->name;
        $this->phone = $details->phone;
        $this->email = $details->email;

        $this->country = $details->country;
        $this->state = $details->state;
        $this->city = $details->city;
        $this->street = $details->street;
        $this->zip = $details->zip;

        $this->ip = $details->ip;

        return $this;
    }

    public function mergeWith(
        self|ShippingDetails $details,
        bool $override = true
    ): self {
        if (!$override) {
            $first = $this;
            $second = $details;
        } else {
            $first = $details;
            $second = $this;
        }
        $this->name = $first->name ?? $second->name;
        $this->phone = $first->phone ?? $second->phone;
        $this->email = $first->email ?? $second->email;

        $this->country = $first->country ?? $second->country;
        $this->state = $first->state ?? $second->state;
        $this->city = $first->city ?? $second->city;
        $this->street = $first->street ?? $second->street;
        $this->zip = $first->zip ?? $second->zip;

        $this->ip = $first->ip ?? $second->ip;

        return $this;
    }

    public function setAddress(
        ?string $country = null,
        ?string $state = null,
        ?string $city = null,
        ?string $street = null,
        ?string $zip = null
    ): self {
        $this->country = $country;
        $this->state = $state;
        $this->city = $city;
        $this->street = $street;
        $this->zip = $zip;

        return $this;
    }

    public function setIp(
        ?string $ip = null
    ): self {
        $this->ip = $ip;

        return $this;
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
                'street1' => $this->street,
                'ip' => $this->ip,
                'zip' => $this->zip,
            ],
        ];
    }
}
