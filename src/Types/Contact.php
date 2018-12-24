<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class Contact extends Base
{
    public function phoneNumber() : ?string
    {
        return $this->__data->phone_number ?? null;
    }

    public function firstName() : string
    {
        return $this->__data->first_name;
    }

    public function lastName() : ?string
    {
        return $this->__data->last_name ?? null;
    }

    public function userId() : ?int
    {
        return $this->__data->user_id ?? null;
    }

    public function vcard() : ?string
    {
        return $this->__data->vcard ?? null;
    }
}