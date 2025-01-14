<?php

namespace Paytabs\Sdk\Holder\Parts\Invoice;

use Paytabs\Sdk\Holder\Parts\AbstractPart;

class Invoice extends AbstractPart
{
    // Invoice Lang
    public ?string $invoiceLang = null;

    // Charges
    public ?float $shippingCharges = null;
    public ?float $extraCharges = null;
    public ?float $extraDiscount = null;
    public ?float $total = null;

    // Dates
    public ?string $activationDate = null;
    public ?string $dueDate = null;
    public ?string $expiryDate = null;

    public ?bool $disableEdit = null;

    public LineItems $lineItems;

    //

    public function build(): array
    {
        $charges = [
            'shipping_charges' => $this->shippingCharges,
            'extra_charges' => $this->extraCharges,
            'extra_discount' => $this->extraDiscount,
            'total' => $this->total,
        ];

        $dates = [
            'activation_date' => $this->activationDate,
            'due_date' => $this->dueDate,
            'expiry_date' => $this->expiryDate,
        ];

        $disableEdit = [
            'disable_edit' => $this->disableEdit,
        ];

        $lang =  [
            'lang' => $this->invoiceLang,
        ];

        $invoiceObj =
            $lang
            + $charges
            + $dates
            + $disableEdit
            + $this->lineItems->build();

        return [
            'invoice' => $invoiceObj,
        ];
    }

    //

    public function setLang(string $lang)
    {
        $this->invoiceLang = $lang;

        return $this;
    }

    public function setCharges(
        float $shippingCharges,
        float $extraCharges,
        float $extraDiscount,
        float $total
    ) {
        $this->shippingCharges = $shippingCharges;
        $this->extraCharges = $extraCharges;
        $this->extraDiscount = $extraDiscount;
        $this->total = $total;

        return $this;
    }

    public function setDates(
        ?string $activationDate,
        ?string $dueDate,
        ?string $expiryDate
    ) {
        $this->activationDate = $activationDate;
        $this->dueDate = $dueDate;
        $this->expiryDate = $expiryDate;

        return $this;
    }

    public function setDisableEdit(bool $disableEdit = true)
    {
        $this->disableEdit = $disableEdit;

        return $this;
    }

    public function setLineItems(LineItems $lineItems)
    {
        $this->lineItems = $lineItems;

        return $this;
    }
}
