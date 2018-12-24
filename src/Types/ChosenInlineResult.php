<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class ChosenInlineResult extends Base
{
    public function resultId() : string
    {
        return $this->__data->result_id;
    }

    public function from() : User
    {
        return new User($this->__data->from);
    }

    public function location() : ?Location
    {
        return isset($this->__data->location) ? new Location($this->__data->location) : null;
    }

    public function inlineMessageId() : ?string
    {
        return $this->__data->inline_message_id ?? null;
    }

    public function query() : string
    {
        return $this->__data->query;
    }
}