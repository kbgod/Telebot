<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class SuccessfulPayment extends Base
{
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

    public function shippingOptionId() : string
    {
        return $this->__data->shipping_option_id;
    }

    public function orderInfo() : ?OrderInfo
    {
        return isset($this->__data->order_info) ? new OrderInfo($this->__data->order_info) : null;
    }

    public function telegramPaymentChargeId() : string
    {
        return $this->__data->telegram_payment_charge_id;
    }

    public function providerPaymentChargeId() : string
    {
        return $this->__data->provider_payment_charge_id;
    }
}