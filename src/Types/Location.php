<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class Location extends Base
{
    public function longitude() : float
    {
        return $this->__data->longitude;
    }

    public function latitude() : float
    {
        return $this->__data->latitude;
    }
}