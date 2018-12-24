<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class EncryptedPassportElement extends Base
{
    public function type() : string
    {
        return $this->__data->type;
    }

    public function data() : ?string
    {
        return $this->__data->data ?? null;
    }

    public function phoneNumber() : ?string
    {
        return $this->__data->phone_number ?? null;
    }

    public function email() : ?string
    {
        return $this->__data->email ?? null;
    }

    /**
     * @return PassportFile[]
     */
    public function files() : ?array
    {
        if(isset($this->__data->files)) {
            foreach ($this->__data->files as $key => $data) {
                $this->__data->files[$key] = new PassportFile($data);
            }
            return $this->__data->files;
        } else return null;
    }

    /**
     * @return PassportFile[]
     */
    public function frontSide() : ?array
    {
        if(isset($this->__data->front_side)) {
            foreach ($this->__data->front_side as $key => $data) {
                $this->__data->front_side[$key] = new PassportFile($data);
            }
            return $this->__data->front_side;
        } else return null;
    }

    /**
     * @return PassportFile[]
     */
    public function reverseSide() : ?array
    {
        if(isset($this->__data->reverse_side)) {
            foreach ($this->__data->reverse_side as $key => $data) {
                $this->__data->reverse_side[$key] = new PassportFile($data);
            }
            return $this->__data->reverse_side;
        } else return null;
    }

    /**
     * @return PassportFile[]
     */
    public function selfie() : ?array
    {
        if(isset($this->__data->selfie)) {
            foreach ($this->__data->selfie as $key => $data) {
                $this->__data->selfie[$key] = new PassportFile($data);
            }
            return $this->__data->selfie;
        } else return null;
    }

    /**
     * @return PassportFile[]
     */
    public function translation() : ?array
    {
        if(isset($this->__data->translation)) {
            foreach ($this->__data->translation as $key => $data) {
                $this->__data->translation[$key] = new PassportFile($data);
            }
            return $this->__data->translation;
        } else return null;
    }

    public function hash() : string
    {
        return $this->__data->hash;
    }
}