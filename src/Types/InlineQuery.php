<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class InlineQuery extends Base
{
    public function id() : string
    {
        return $this->__data->id;
    }

    public function from() : User
    {
        return new User($this->__data->from);
    }

    public function location() : ?Location
    {
        return isset($this->__data->location) ? new Location($this->__data->location) : null;
    }

    public function query() : string
    {
        return $this->__data->query;
    }

    public function offset() : string
    {
        return $this->__data->offset;
    }
}