<?php

namespace Paytabs\Sdk\Holder\Parts;

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
        return $this->setContact($name, $phone, $email);
    }

    public function setContact(
        ?string $name = null,
        ?string $phone = null,
        ?string $email = null
    ) {
        if ($this->readNextIf() === false) {
            return $this;
        }

        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;

        return $this;
    }

    public function setAddress(
        ?string $country = null,
        ?string $state = null,
        ?string $city = null,
        ?string $street = null,
        ?string $zip = null
    ): self {
        if ($this->readNextIf() === false) {
            return $this;
        }

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
        if ($this->readNextIf() === false) {
            return $this;
        }

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
