<?php

namespace Telebot\Addons;

use Telebot\Core\Eventer;

class Scene extends Eventer
{
    protected $handlers = [];
    private $enter;
    private $leave;
    public $name;

    /**
     * Scene constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function import()
    {
        return ['enter' => $this->enter, 'leave' => $this->leave, 'handlers' => $this->handlers];
    }

    public function enter($func)
    {
        $this->enter = $func;
    }

    public function leave($func)
    {
        $this->leave = $func;
    }
}