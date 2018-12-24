<?php

namespace Telebot\Addons\Inline;


use Telebot\Addons\Inline\Base\InlineQueryResult;

class Result
{
    private $results;

    public function add()
    {
        $results = func_get_args();
        foreach ($results as $result) {
            $this->addResult($result);
        }
    }

    public function addArray($results)
    {
        call_user_func_array([$this, 'add'], $results);
    }

    private function addResult(InlineQueryResult $result)
    {
        $this->results[] = $result->getAsObject();
    }

    public function __toString()
    {
        return json_encode($this->results);
    }

}