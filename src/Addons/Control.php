<?php

namespace Telebot\Addons;

use Telebot\Core\Context;
use RedBeanPHP\R;
use Telebot\Interfaces\UserInterface;

class Control implements UserInterface
{
    private $__user;
    private $__storage;

    public function __construct(Context $ctx)
    {
        R::useWriterCache(false);
        $user = R::findOne('tgusers', 'user_id = ?', [$ctx->getUserID()]); #- Привело к багу
        R::useWriterCache(true);
        //$user = R::convertToBean('tgusers', R::getRow('select * from tgusers where user_id = ?', [$ctx->getUserID()]));
        if($user==null) {
            $user = R::dispense('tgusers');
            $user->user_id = $ctx->getUserID();
            $user->state = '';
            $user->storage = $this->initStorage();
            $user = R::load('tgusers', R::store($user));

        }
        $this->__user = $user;
        $this->openStorage();
    }

    public function initStorage()
    {
        return json_encode([]);
    }

    private function openStorage()
    {
        $this->__storage = json_decode($this->__user->storage, true);
    }

    public function addToStorage($element, $value) {
        $this->__storage[$element] = $value;
        return $this;
    }

    public function getFromStorage($element) {
        $el = explode('.', $element);
        $output = $this->__storage;
        foreach ($el as $e) {
            if(isset($output[$e])) $output = $output[$e];
            else $output = null;
        }
        return $output;
    }

    public function delStorageElement($element) {
        unset($this->__storage[$element]);
        return $this;
    }

    /**
     * @return $this
     */
    public function clearStorage() {
        $this->__storage = [];
        return $this;
    }

    /**
     * @return string
     */
    public function getState() {
        return $this->state;
    }

    public function setState($key) {
        $this->state = $key;
    }

    public function saveStorage() {
        $this->storage = json_encode($this->__storage);
    }

    public function set(array $components, $values)
    {
        if (is_array($values)) {
            if(count($components)==count($values)) {
                foreach ($components as $k => $component) {
                    $this->__user->$component = $values[$k];
                }
                $this->save();
            }
        } else {
            foreach ($components as $k => $component) {
                $this->__user->$component = $values;
            }
            $this->save();
        }
    }

    public function __get($component)
    {
        return $this->__user->$component;
    }

    public function __set($component, $value)
    {
        $this->__user->$component = $value;
        $this->save();
    }

    private function save()
    {
        R::store($this->__user);
    }
}