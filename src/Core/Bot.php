<?php

namespace Telebot\Core;


use Telebot\Addons\Scene,
    Telebot\Addons\Functions;

use RedBeanPHP\R;
use Telebot\Types\Update;

class Bot extends Eventer
{

    const RUN_AS_POLL = 0;
    const RUN_AS_HOOK = 1;

    private $api;
    /**
     * $ctx Context
     */
    public $ctx;

    private $loopHandler;

    protected $handlers = [];
    private $scenes = [];

    public $addons = [];

    public function __construct($settings)
    {
        $this->api = new API($settings);
    }

    public function ctx(): Context
    {
        return $this->ctx;
    }

    public function api(): API
    {
        return $this->api;
    }

    public function loop($loopHandler)
    {
        $this->loopHandler = $loopHandler;
    }

    public function run()
    {
        switch ($this->api->settings['run_type']) {
            case self::RUN_AS_POLL:
                $update_id = 0;
                while (true) {
                    $updates = $this->api->getUpdates(['offset' => $update_id, 'timeout' => 600]);
                    if ($updates->ok) {
                        foreach ($updates->result as $update) {
                            $update_id = $update->update_id + 1;
                            $this->processUpdate($update);
                        }
                    } else {
                        $this->api->trace('После критической ошибки, бот не был запущен в режиме Long Poll');
                        break;
                    }
                }
                break;

            case self::RUN_AS_HOOK:
                if (isset($_REQUEST)) {
                    $this->processUpdate(json_decode(file_get_contents('php://input')));
                }
                break;
        }
    }

    public function processUpdate($update)
    {
        if(!isset($update->update_id)) return;
        $this->ctx = new Context(new Update($update), $this->api, $this->scenes);
        if(is_callable($this->loopHandler)) ($this->loopHandler)(new Update($update));
        if($this->api->settings['timing']) {
            $this->api->trace('#');
            $this->api->trace('#Update: ' . $update->update_id);
            Functions::ctime('update');
        }
        if ($this->loadAddons()) {
            if($this->ctx->usedControl()) {
                $scene = (isset($this->scenes[$this->ctx()->getState()])) ? $this->scenes[$this->ctx()->getState()] : null;
                if(is_array($scene)) {
                    $handlers = $scene['handlers'];
                    foreach ($handlers as $handler) {
                        if ($handler($this->ctx) === true) break;
                    }
                    return;
                }
            }
            if($this->api->settings['timing']) Functions::ctime('handlers');
            foreach ($this->handlers as $id => $handler) {
                if($this->api->settings['timing']) Functions::ctime('handler');
                if ($handler($this->ctx) === true) {
                    if($this->api->settings['timing']) $this->api->trace('#[Handlers] - Событие ID:'.$id.' = '.Functions::ctime('handler'));
                    break;
                }
            }
        }
        if($this->api->settings['timing']) {
            $this->api->trace('#Время затраченное на обработку событий: ' . Functions::ctime('handlers'));
            $this->api->trace('#Всего: ' . Functions::ctime('update'));
            $this->api->trace('#');
        }
    }

    private function loadAddons()
    {
        $ctx = $this->ctx;
        foreach ($this->addons as $addon) {
            $ctx = $addon($ctx);
            if (!$ctx) {
                $this->api->trace('#[WARNING] Usage not returned the Context.');
                return false;
            }
        }
        $this->ctx = $ctx;
        return true;
    }

    public function addScene(Scene $scene)
    {
        $this->scenes[$scene->name] = $scene->import();
    }

    public function addScenes()
    {
        $scenes = func_get_args();
        foreach ($scenes as $scene) {
            $this->addScene($scene);
        }
    }

    public function usage($func)
    {
        $this->addons[] = $func;
    }

    public function addUsages()
    {
        $usages = func_get_args();
        foreach ($usages as $usage) {
            $this->usage($usage);
        }
    }

}