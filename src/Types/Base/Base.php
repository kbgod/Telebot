<?php

namespace Telebot\Types\Base;

class Base
{
    protected $__data;

    public function __construct($data)
    {
        $this->__data = $data;
    }

    public function exists($param)
    {
        return isset($this->__data->$param);
    }

    public function __get($param)
    {
        return $this->__data->$param;
    }
}