<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class UserProfilePhotos extends Base
{
    public function totalCount() : int
    {
        return $this->__data->total_count;
    }

    /**
     * @return PhotoSize[][]
     */
    public function photos() : array
    {
        foreach($this->__data->photos as $aopKey => $arrayOfPhotos ) {
            foreach ($arrayOfPhotos as $key => $photo) {
                $arrayOfPhotos[$key] = new PhotoSize($photo);
            }
            $this->__data->photos[$aopKey] = $arrayOfPhotos;
        }
        return $this->__data->photos;
    }
}