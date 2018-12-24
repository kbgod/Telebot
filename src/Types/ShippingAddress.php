<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class ShippingAddress extends Base
{
    public function countryCode() : string
    {
        return $this->__data->country_code;
    }

    public function state() : string
    {
        return $this->__data->state;
    }

    public function city() : string
    {
        return $this->__data->city;
    }

    public function streetLine1() : string
    {
        return $this->__data->street_line1;
    }

    public function streetLine2() : string
    {
        return $this->__data->street_line2;
    }

    public function postCode() : string
    {
        return $this->__data->post_code;
    }
}