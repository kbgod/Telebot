<?php

namespace Telebot\Addons\Inline;


use Telebot\Addons\Inline\Base\InputMessageContent;

class InputTextMessageContent extends InputMessageContent
{
    public function text($text)
    {
        $this->out['message_text'] = $text;
        return $this;
    }

    public function parseMode($parse_mode)
    {
        $this->out['parse_mode'] = $parse_mode;
        return $this;
    }

    public function disableWebPagePreview(bool $disable_web_page_preview)
    {
        $this->out['disable_web_page_preview'] = $disable_web_page_preview;
        return $this;
    }
}