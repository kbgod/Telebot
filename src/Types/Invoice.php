<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class Invoice extends Base
{
    public function title() : string
    {
        return $this->__data->title;
    }

    public function description() : string
    {
        return $this->__data->description;
    }

    public function startParameter() : string
    {
        return $this->__data->start_parameter;
    }

    public function currency() : string
    {
        return $this->__data->currency;
    }

    public function totalAmount() : int
    {
        return $this->__data->total_amount;
    }
}