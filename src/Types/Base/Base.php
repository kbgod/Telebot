<?php

namespace Telebot\Types\Base;

class Base
{
    protected $__data;

    public function __construct($data)
    {
        $this->__data = $data;
    }
}