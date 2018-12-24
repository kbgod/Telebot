<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class PreCheckoutQuery extends Base
{
    public function id() : string
    {
        return $this->__data->id;
    }

    public function from() : User
    {
        return new User($this->__data->from);
    }

    public function currency() : string
    {
        return $this->__data->currency;
    }

    public function totalAmount() : int
    {
        return $this->__data->total_amount;
    }

    public function invoicePayload() : string
    {
        return $this->__data->invoice_payload;
    }

    public function shippingOptionId() : ?string
    {
        return $this->__data->shipping_option_id ?? null;
    }

    public function orderInfo() : ?OrderInfo
    {
        return isset($this->__data->order_info) ? new OrderInfo($this->__data->order_info) : null;
    }
}