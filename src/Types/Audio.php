<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class Audio extends Base
{

    public function fileId() : string
    {
        return $this->__data->file_id;
    }

    public function duration() : int
    {
        return $this->__data->duration;
    }

    public function performer() : ?string
    {
        return $this->__data->performer ?? null;
    }

    public function title() : ?string
    {
        return $this->__data->title ?? null;
    }

    public function mimeType() : ?string
    {
        return $this->__data->mime_type ?? null;
    }

    public function fileSize() : ?int
    {
        return $this->__data->file_size ?? null;
    }

    public function thumb() : ?PhotoSize
    {
        return isset($this->__data->thumb) ? new PhotoSize($this->__data->thumb) : null;
    }
}