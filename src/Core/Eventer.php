<?php

namespace Telebot\Core;


use Telebot\Types\Message;

abstract class Eventer
{
    protected $customParams = [
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

    protected $customTypes = [
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

    protected $handlers;

    protected function addHandler($func)
    {
        $this->handlers[] = $func;
    }

    protected function initParams($param)
    {
        preg_match_all("{([A-Za-z_]+:[A-Za-z_]+)}", $param, $matches);
        $matches = $matches[0];
        foreach ($matches as $preset) {
            list($key, $type) = explode(':', $preset);
            $param = str_replace('{' . $preset . '}', '(?<' . $key . '>' . $this->customTypes[$type] . ')', $param);
        }
        return str_replace(array_flip($this->customParams), $this->customParams, $param);
    }

    protected function parseCommands(Message $message)
    {
        $commands = [];
        foreach ($message->entities() as $entity) {
            if ($entity->type() == 'bot_command') {
                $commands[] = mb_substr($message->text(), $entity->offset(), $entity->length());
            }
        }
        return $commands;
    }

    protected function parseParams($matches)
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
            if (!$ctx->update->exists('message') and !$ctx->update->message()->exists('text')) return false;
            if (preg_match($text, $ctx->getText(), $matches)) {
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
            if (!$ctx->update->exists('message') and !$ctx->update->message()->exists('entities')) return false;
            $commands = $this->parseCommands($ctx->update->message());
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
            if ($ctx->update->exists('message') and $ctx->update->message()->exists($field)) {
                $func($ctx);
                return true;
            }
            return false;
        });
    }

    public function onUpdate($field, $func)
    {
        $this->addHandler(function (Context $ctx) use ($field, $func) {
            if ($ctx->update->exists($field)) {
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
            if (preg_match($act, $ctx->callbackQuery()->data(), $matches)) {
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
            if (preg_match($query, $ctx->inlineQuery()->query(), $matches)) {
                $ctx->params = $this->parseParams($matches);
                $func($ctx, $matches);
                return true;
            }
            return false;
        });
    }
}