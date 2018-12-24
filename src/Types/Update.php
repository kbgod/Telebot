<?php

namespace Telebot\Types;

use Telebot\Types\Base\Base;

class Update extends Base
{
    public function updateId() : int
    {
        return $this->__data->update_id;
    }

    public function message() : ?Message
    {
        return isset($this->__data->message) ? new Message($this->__data->message) : null;
    }

    public function editedMessage() : ?Message
    {
        return isset($this->__data->edited_message) ? new Message($this->__data->edited_message) : null;
    }

    public function channelPost() : ?Message
    {
        return isset($this->__data->channel_post) ? new Message($this->__data->channel_post) : null;
    }

    public function editedChannelPost() : ?Message
    {
        return isset($this->__data->edited_channel_post) ? new Message($this->__data->edited_channel_post) : null;
    }

    public function inlineQuery() : ?InlineQuery
    {
        return isset($this->__data->inline_query) ? new InlineQuery($this->__data->inline_query) : null;
    }

    public function chosenInlineResult() : ?ChosenInlineResult
    {
        return isset($this->__data->chosen_inline_result) ? new ChosenInlineResult($this->__data->chosen_inline_result) : null;
    }

    public function callbackQuery() : ?CallbackQuery
    {
        return isset($this->__data->callback_query) ? new CallbackQuery($this->__data->callback_query) : null;
    }

    public function shippingQuery() : ?ShippingQuery
    {
        return isset($this->__data->shipping_query) ? new ShippingQuery($this->__data->shipping_query) : null;
    }

    public function preCheckoutQuery() : ?PreCheckoutQuery
    {
        return isset($this->__data->pre_checkout_query) ? new PreCheckoutQuery($this->__data->pre_checkout_query) : null;
    }

}