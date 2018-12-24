<?php

namespace Telebot\Addons;

use Telebot\Core\Context;

class Scene
{

    private $customParams = [ // Для onText
        '{INT}' => '([\d]+)',
        '{STR}' => '([\w]+)',
        '{NUM}' => '([\d])',
        '{SYM}' => '([\w])',
        '{ENG}' => '([A-Za-z]+)',
        '{ENG_S}' => '([A-Za-z\s]+)',
        '{RUS}' => '([А-Яа-яёЁ]+)',
        '{RUS_S}' => '([А-Яа-яёЁ\s]+)',
        '{UKR}' => '([А-Яа-яЇїІіЄєҐґ]+)',
        '{UKR_S}' => '([А-Яа-яЇїІіЄєҐґ\s]+)',
    ];

    private $customTypes = [
        'int' => '[\d]+',
        'num' => '[\d]',
        'str' => '[\w]+',
        'chr' => '[\w]',
        'eng' => '[A-Za-z]+',
        'eng_s' => '[A-Za-z\s]+',
        'rus' => '[А-Яа-яёЁ]+',
        'rus_s' => '[А-Яа-яёЁ\s]+',
        'ukr' => '[А-Яа-яЇїІіЄєҐґ]+',
        'ukr_s' => '[А-Яа-яЇїІіЄєҐґ\s]+'
    ];
    private $handlers = [];
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

    public function addHandler($func)
    {
        $this->handlers[] = $func;
    }

    private function initParams($param)
    {
        preg_match_all("{([A-Za-z_]+:[A-Za-z_]+)}", $param, $matches);
        $matches = $matches[0];
        foreach ($matches as $preset) {
            list($key, $type) = explode(':', $preset);
            $param = str_replace('{' . $preset . '}', '(?<' . $key . '>' . $this->customTypes[$type] . ')', $param);
        }
        return str_replace(array_flip($this->customParams), $this->customParams, $param);
    }

    private function parseCommands($message)
    {
        $commands = [];
        foreach ($message->entities as $entity) {
            if ($entity->type == 'bot_command') {
                $commands[] = mb_substr($message->text, $entity->offset, $entity->length);
            }
        }
        return $commands;
    }

    private function parseParams($matches)
    {
        $output = [];
        foreach ($matches as $k => $match) {
            if (is_string($k)) $output[$k] = $match;
        }
        return $output;
    }

    public function txt($text, $func, $regex = false, $anyCase = true)
    {
        $case = $anyCase ? 'i' : '';
        $text = !$regex ? '#^' . $this->initParams($text) . '$#u' . $case : $text;
        $this->addHandler(function (Context $ctx) use ($text, $func) {
            if (!isset($ctx->update->message->text)) return false;
            if (preg_match($text, $ctx->update->message->text, $matches)) {
                $ctx->params = $this->parseParams($matches);
                $func($ctx, $matches);
                return true;
            }
            return false;
        });
    }

    public function cmd($command, $func)
    {
        $this->addHandler(function (Context $ctx) use ($command, $func) {
            if (!isset($ctx->update->message->entities)) return false;
            $commands = $this->parseCommands($ctx->update->message);
            if (count($commands) == 0) return false;
            foreach ($commands as $cmd) {
                if ('/' . $command == $cmd) {
                    $func($ctx);
                    return true;
                }
            }
            return false;
        });
    }

    public function hears($text, $func)
    {
        if (is_array($text)) {
            foreach ($text as $string) {
                $this->txt("/$string/iu", $func, true);
            }
        } else $this->txt("/$text/iu", $func, true);
    }

    public function onMessage($field, $func)
    {
        $this->addHandler(function (Context $ctx) use ($field, $func) {
            if (isset($ctx->update->message->$field)) {
                $func($ctx);
                return true;
            }
            return false;
        });
    }

    public function onUpdate($field, $func)
    {
        $this->addHandler(function (Context $ctx) use ($field, $func) {
            if (isset($ctx->update->$field)) {
                $func($ctx);
                return true;
            }
            return false;
        });
    }

    public function act($act, $func, $regex = false, $anyCase = true)
    {
        $case = $anyCase ? 'i' : '';
        $act = !$regex ? '#^' . $this->initParams($act) . '$#u' . $case : $act;
        $this->addHandler(function (Context $ctx) use ($act, $func) {
            if (preg_match($act, $ctx->callbackQuery()->data, $matches)) {
                $ctx->params = $this->parseParams($matches);
                $func($ctx, $matches);
                return true;
            }
            return false;
        });
    }

    public function inlQuery($query, $func, $regex = false, $anyCase = true)
    {
        $case = $anyCase ? 'i' : '';
        $query = !$regex ? '#^' . $this->initParams($query) . '$#u' . $case : $query;
        $this->addHandler(function(Context $ctx) use ($query, $func) {
            if (preg_match($query, $ctx->inlineQuery()->query, $matches)) {
                $ctx->params = $this->parseParams($matches);
                $func($ctx, $matches);
                return true;
            }
            return false;
        });
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