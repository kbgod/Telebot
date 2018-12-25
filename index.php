<?php

require_once 'vendor/autoload.php';

use Telebot\Core\Bot,
    Telebot\Core\Context;
use Telebot\Addons\Control,
    Telebot\Addons\Scene,
    Telebot\Addons\Keyboard,
    Telebot\Addons\MediaGroup,
    Telebot\Addons\Calendar;

use Telebot\Addons\Inline\Result, // Инлайн
    Telebot\Addons\Inline\Article,
    \Telebot\Addons\Inline\InputTextMessageContent;

use RedBeanPHP\R;

//R::setup( 'mysql:host=127.0.0.1;dbname=tgframework', 'root', '');


$settings['api_token'] = '';
$settings['debug_mode'] = true;
//$settings['timing'] = true;

$bot = new Bot($settings);
$bot->loop(function($update) {
    //var_dump($update);
});
$bot->onMessage('sticker', function (Context $ctx){
    $ctx->reply('Nice:)');
});

$bot->run();