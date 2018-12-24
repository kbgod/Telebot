<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class VideoNote extends Base
{
    public function fileId() : string
    {
        return $this->__data->file_id;
    }

    public function length() : int
    {
        return $this->__data->length;
    }

    public function duration() : int
    {
        return $this->__data->duration;
    }

    public function thumb() : ?PhotoSize
    {
        return isset($this->__data->thumb) ? new PhotoSize($this->__data->thumb) : null;
    }

    public function fileSize() : ?int
    {
        return $this->__data->file_size ?? null;
    }
}