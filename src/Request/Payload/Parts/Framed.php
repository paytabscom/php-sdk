<?php

declare(strict_types=1);

namespace Paytabs\Sdk\Request\Payload\Parts;

use Paytabs\Sdk\Enums\FramedTarget;

class Framed extends AbstractPart
{
    private bool $framed;
    private FramedTarget $framedTarget;
    private ?string $messageTarget;
    private ?bool $forceFullUi;

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
            'framed_return_parent' => FramedTarget::ReturnParent === $this->framedTarget,
            'framed_return_top' => FramedTarget::ReturnTop === $this->framedTarget,
            'force_full_ui' => $this->forceFullUi,
            'framed_message_target' => $this->messageTarget,
        ];
    }
}
