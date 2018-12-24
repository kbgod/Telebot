<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class ShippingQuery extends Base
{
    public function id() : string
    {
        return $this->__data->id;
    }

    public function from() : User
    {
        return new User($this->__data->from);
    }

    public function invoicePayload() : string
    {
        return $this->__data->invoice_payload;
    }

    public function shippingAddress() : ShippingAddress
    {
        return new ShippingAddress($this->__data->shipping_address);
    }
}