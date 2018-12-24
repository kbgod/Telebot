<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class Sticker extends Base
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

    public function thumb() : ?PhotoSize
    {
        return isset($this->__data->thumb) ? new PhotoSize($this->__data->thumb) : null;
    }

    public function emoji() : ?string
    {
        return $this->__data->emoji ?? null;
    }

    public function setName() : ?string
    {
        return $this->__data->set_name ?? null;
    }

    public function maskPosition() : ?MaskPosition
    {
        return isset($this->__data->mask_position) ? new MaskPosition($this->__data->mask_position) : null;
    }

    public function fileSize() : ?int
    {
        return $this->__data->file_size ?? null;
    }
}