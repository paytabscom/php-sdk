<?php

namespace Paytabs\Sdk\Holder\Parts;

use Paytabs\Sdk\Enums\FramedTarget;
use Paytabs\Sdk\Holder\PartInterface;

class Framed implements PartInterface
{
    private bool $framed;
    private FramedTarget $framedTarget;
    private string $messageTarget;
    private bool $forceFullUi;

    public function __construct(
        bool $framed,
        FramedTarget $framedTarget,
        ?string $messageTarget = null,
        bool $forceFullUi = false
    ) {
        $this->framed = $framed;
        $this->framedTarget = $framedTarget;
        $this->messageTarget = $messageTarget;
        $this->forceFullUi = $forceFullUi;
    }

    public function build(): array
    {
        return [
            'framed' => $this->framed,
            'framed_return_parent' => $this->framedTarget == FramedTarget::ReturnParent,
            'framed_return_top' => $this->framedTarget == FramedTarget::ReturnTop,
            'force_full_ui' => $this->forceFullUi,
            'framed_message_target' => $this->messageTarget,
        ];
    }
}
