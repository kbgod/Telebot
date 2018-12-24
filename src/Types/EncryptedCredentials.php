<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class EncryptedCredentials extends Base
{
    public function data() : string
    {
        return $this->__data->data;
    }

    public function hash() : string
    {
        return $this->__data->hash;
    }

    public function secret() : string
    {
        return $this->__data->secret;
    }
}