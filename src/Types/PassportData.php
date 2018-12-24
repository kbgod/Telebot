<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class PassportData extends Base
{
    /**
     * @return EncryptedPassportElement[]
     */
    public function data() : ?array
    {
        if(isset($this->__data->data)) {
            foreach ($this->__data->data as $key => $data) {
                $this->__data->data[$key] = new EncryptedPassportElement($data);
            }
            return $this->__data->data;
        } else return null;
    }

    public function credentials() : ?EncryptedCredentials
    {
        return isset($this->__data->credentials) ? new EncryptedCredentials($this->__data->credentials) : null;
    }
}