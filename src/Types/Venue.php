<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class Venue extends Base
{
    public function location() : Location
    {
        return new Location($this->__data->location);
    }

    public function title() : string
    {
        return $this->__data->title;
    }

    public function address() : string
    {
        return $this->__data->address;
    }

    public function foursquareId() : ?string
    {
        return $this->__data->foursquare_id ?? null;
    }

    public function foursquareType() : ?string
    {
        return $this->__data->foursquare_type ?? null;
    }
}