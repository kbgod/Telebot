<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class User extends Base
{

    public function id() : int
    {
        return $this->__data->id;
    }

    public function isBot() : bool
    {
        return $this->__data->is_bot;
    }

    public function firstName() : string
    {
        return $this->__data->first_name;
    }

    public function lastName() : ?string
    {
        return $this->__data->last_name ?? null;
    }

    public function username() : ?string
    {
        return $this->__data->username ?? null;
    }

    public function languageCode() : ?string
    {
        return $this->__data->language_code ?? null;
    }
}