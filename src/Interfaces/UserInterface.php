<?php

namespace Telebot\Interfaces;


interface UserInterface
{
    public function getState();

    public function setState($state);
}