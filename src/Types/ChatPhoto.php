<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class ChatPhoto extends Base
{

    public function smallFileId() : string
    {
        return $this->__data->small_file_id;
    }

    public function bigFileId() : string
    {
        return $this->__data->big_file_id;
    }
}