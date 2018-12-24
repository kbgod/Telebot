<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class PassportFile extends Base
{
    public function fileId() : string
    {
        return $this->__data->file_id;
    }

    public function fileSize() : int
    {
        return $this->__data->file_size;
    }

    public function fileDate() : int
    {
        return $this->__data->file_date;
    }
}