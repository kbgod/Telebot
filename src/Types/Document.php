<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class Document extends Base
{
    public function fileId() : string
    {
        return $this->__data->file_id;
    }

    public function thumb() : ?PhotoSize
    {
        return isset($this->__data->thumb) ? new PhotoSize($this->__data->thumb) : null;
    }

    public function fileName() : ?string
    {
        return $this->__data->file_name ?? null;
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