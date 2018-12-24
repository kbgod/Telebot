<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class OrderInfo extends Base
{
    public function name() : ?string
    {
        return $this->__data->name ?? null;
    }

    public function phoneNumber() : ?string
    {
        return $this->__data->phone_number ?? null;
    }

    public function email() : ?string
    {
        return $this->__data->email ?? null;
    }

    public function shippingAddress() : ?ShippingAddress
    {
        return isset($this->__data->shipping_address) ? new ShippingAddress($this->__data->shipping_address) : null;
    }
}