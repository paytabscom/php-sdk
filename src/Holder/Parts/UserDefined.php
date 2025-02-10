<?php

namespace Paytabs\Sdk\Holder\Parts;

class UserDefined extends AbstractPart
{


    public array $userDefined;

    public function __construct(array $userDefined) {
        $this->userDefined = $userDefined;
    }



    public function setUDF()
    {

        for ($i = 1; $i <= 9; $i++) {
            $param = "udf$i";
            if ($$param != null) {
                $this->userDefined[$param] = $$param;
            }
        }

        $this->user_defined = [
            'user_defined' => $this->userDefined
        ];

        return $this;
    }

    public function build(): array
    {
        
    }
}
