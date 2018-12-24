<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class MessageEntity extends Base
{

    public function type() : string
    {
        return $this->__data->type;
    }

    public function offset() : int
    {
        return $this->__data->offset;
    }

    public function length() : int
    {
        return $this->__data->length;
    }

    public function url() : ?string
    {
        return $this->__data->url ?? null;
    }

    public function user() : ?User
    {
        return isset($this->__data->user) ? new User($this->__data->user) : null;
    }
}