<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class MaskPosition extends Base
{
    public function point() : string
    {
        return $this->__data->point;
    }

    public function xShift() : float
    {
        return $this->__data->x_shift;
    }

    public function yShift() : float
    {
        return $this->__data->y_shift;
    }

    public function scale() : float
    {
        return $this->__data->scale;
    }
}