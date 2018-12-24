<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class Game extends Base
{
    public function title() : string
    {
        return $this->__data->title;
    }

    public function description() : string
    {
        return $this->__data->description;
    }

    public function photo() : array
    {
        return $this->__data->photo;
    }

    public function text() : ?string
    {
        return $this->__data->text ?? null;
    }

    /**
     * @return MessageEntity[]
     */
    public function text_entities() : ?array
    {
        if(isset($this->__data->text_entities)) {
            foreach ($this->__data->text_entities as $key => $entity) {
                $this->__data->text_entities[$key] = new MessageEntity($entity);
            }
            return $this->__data->text_entities;
        } else return null;
    }

    public function animation() : ?Animation
    {
        return isset($this->__data->animation) ? new Animation($this->__data->animation) : null;
    }
}