<?php

namespace Response\Payloads;

class CompletedArray extends Payment
{
    /** @var array[Completed] */
    public array $transactions;
}
