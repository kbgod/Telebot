<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class CallbackQuery extends Base
{
    public function id() : int
    {
        return $this->__data->id;
    }

    public function from() : User
    {
        return new User($this->__data->from);
    }

    public function message() : ?Message
    {
        return isset($this->__data->message) ? new Message($this->__data->message) : null;
    }

    public function inlineMessageId() : ?string
    {
        return $this->__data->inline_message_id ?? null;
    }

    public function chatInstance() : string
    {
        return $this->__data->chat_instance;
    }

    public function data() : ?string
    {
        return $this->__data->data ?? null;
    }

    public function gameShortName() : ?string
    {
        return $this->__data->game_short_name ?? null;
    }
}