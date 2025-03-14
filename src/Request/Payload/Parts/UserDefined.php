<?php

namespace Paytabs\Sdk\Request\Payload\Parts;

class UserDefined extends AbstractPart
{
    protected const KEY = 'user_defined';

    public ?string $udf1 = null;
    public ?string $udf2 = null;
    public ?string $udf3 = null;
    public ?string $udf4 = null;
    public ?string $udf5 = null;
    public ?string $udf6 = null;
    public ?string $udf7 = null;
    public ?string $udf8 = null;
    public ?string $udf9 = null;

    public function setUDF1(?string $udf1): self
    {
        $this->udf1 = $udf1;

        return $this;
    }

    public function setUDF2(?string $udf2): self
    {
        $this->udf2 = $udf2;

        return $this;
    }

    public function setUDF3(?string $udf3): self
    {
        $this->udf3 = $udf3;

        return $this;
    }

    public function setUDF4(?string $udf4): self
    {
        $this->udf4 = $udf4;

        return $this;
    }

    public function setUDF5(?string $udf5): self
    {
        $this->udf5 = $udf5;

        return $this;
    }

    public function setUDF6(?string $udf6): self
    {
        $this->udf6 = $udf6;

        return $this;
    }

    public function setUDF7(?string $udf7): self
    {
        $this->udf7 = $udf7;

        return $this;
    }

    public function setUDF8(?string $udf8): self
    {
        $this->udf8 = $udf8;

        return $this;
    }

    public function setUDF9(?string $udf9): self
    {
        $this->udf9 = $udf9;

        return $this;
    }

    public function build(): array
    {
        $array = [
            static::KEY => [
                'udf1' => $this->udf1,
                'udf2' => $this->udf2,
                'udf3' => $this->udf3,
                'udf4' => $this->udf4,
                'udf5' => $this->udf5,
                'udf6' => $this->udf6,
                'udf7' => $this->udf7,
                'udf8' => $this->udf8,
                'udf9' => $this->udf9,
            ],
        ];

        foreach ($array as $key => $value) {
            if (null === $value) {
                unset($array[$key]);
            }
        }

        return $array;
    }
}
