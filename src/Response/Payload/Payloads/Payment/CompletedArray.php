<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Response\Payload\Payloads\Payment;

use Paytabs\Sdk\Response\Payload\Payloads\Paytabs;

class CompletedArray extends Paytabs
{
    /** @var Completed[] */
    public array $transactions = [];

    /**
     * @throws \InvalidArgumentException if the payload is not valid or cannot be mapped.
     * @return static
     */
    public function getMapped(): static
    {
        $jsonMapper = new \JsonMapper();

        try {
            $this->transactions = $jsonMapper->mapArray(
                $this->getAsJson(),
                [],
                Completed::class
            );
        } catch (\JsonMapper_Exception $th) {
            throw new \InvalidArgumentException('Failed to map payload', 0, $th);
        } catch (\Throwable $th) {
            throw new \InvalidArgumentException('Failed to map', 0, $th);
        }

        return $this;
    }
}
