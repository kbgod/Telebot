<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class File extends Base
{
    public function fileId() : string
    {
        return $this->__data->file_id;
    }

    public function fileSize() : ?int
    {
        return $this->__data->file_size ?? null;
    }

    public function filePath() : ?string
    {
        return $this->__data->file_path ?? null;
    }
}