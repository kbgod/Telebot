<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class Voice extends Base
{
    public function fileId() : string
    {
        return $this->__data->file_id;
    }

    public function duration() : int
    {
        return $this->__data->duration;
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