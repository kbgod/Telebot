<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class PhotoSize extends Base
{
    public function fileId() : string
    {
        return $this->__data->file_id;
    }

    public function width() : int
    {
        return $this->__data->width;
    }

    public function height() : int
    {
        return $this->__data->height;
    }

    public function fileSize() : ?int
    {
        return $this->__data->file_size ?? null;
    }
}