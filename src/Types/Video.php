<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class Video extends Base
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

    public function duration() : int
    {
        return $this->__data->duration;
    }

    public function thumb() : ?PhotoSize
    {
        return isset($this->__data->thumb) ? new PhotoSize($this->__data->thumb) : null;
    }

    public function mimeType() : ?string
    {
        return $this->__data->mime_type ?? null;
    }

    public function fileSize() : ?int
    {
        return $this->__data->file_size ?? null;
    }
}