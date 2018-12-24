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

R::setup( 'mysql:host=127.0.0.1;dbname=tgframework',
    'root', '');


$settings['api_token'] = '644391805:AAGZkRzPHmFUYeO38OnZFVNguBAL3ypG5XY';
$settings['debug_mode'] = true;

$bot = new Bot($settings);

$bot->usage(function (Context $ctx) {
   $ctx->setUserControl(new Control($ctx));
   return $ctx;
});

$bot->txt('меню', function(Context $ctx) {
    $menu = new Keyboard(Keyboard::INLINE);
    $menu->row('Заявки')
         ->row('Пользователи');
    $ctx->reply('Главное меню', $menu);
});

$bot->txt('/start {str:str}', function (Context $ctx){
    $ctx->reply($ctx->params['str']);
});

$bot->inlQuery('статьи', function (Context $ctx){
    $article = new Article('777');
    $article->title('Тестовая статья 1');
    $article->description('Тестовое описание 1');
    $article->inputMessageContent( (new InputTextMessageContent())->text('Тестовое содержимое статьи 1') );

    $article2 = new Article('778');
    $article2->title('Тестовая статья 2');
    $article2->description('Тестовое описание 2');
    $article2->inputMessageContent( (new InputTextMessageContent())->text('Тестовое содержимое статьи 2') );
    $result = new Result();
    $result->add($article, $article2);
    $ctx->ansInlineQuery($ctx->getInlineQueryID(), $result);
});

$bot->onUpdate('inline_query', function (Context $ctx){
    $options = [
        'switch_pm_text' => 'Напишите мне в лс',
        'switch_pm_parameter' => 'code'
    ];
    $ctx->ansInlineQuery($ctx->getInlineQueryID(), null, $options);
});




$bot->run();