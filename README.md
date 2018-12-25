# Telebot

### Examples
  
```php
$settings = [
        'api_token'  => '',
        'base_url'   => 'https://api.telegram.org/',
        'username'   => '',
        'use_proxy'  => false,
        'run_type'   => 0, // 0 - Polling, 1 - Webhook
        'hook_reply' => true,
        'debug_mode' => false,
        'timing'     => false,
        'log_mode'   => false,
        'log_path'   => __DIR__ . DIRECTORY_SEPARATOR . 'tb_log',
        'proxy'      => [
            'authorization' => '',
            'server'        => '',
            'proxy_type'    => '',
        ]
];

use Telebot\Core\Bot,
    Telebot\Core\Context;
    
$bot = new Bot($settings);
$bot->cmd('start', function(Context $ctx){
    $ctx->reply('Hi! Send me a sticker');
});

$bot->onMessage('sticker', function (Context $ctx){
    $ctx->reply('Nice:)');
});

$bot->hears('bye', function (Context $ctx){
    $ctx->reply('bye bye...');
});

$bot->txt('say {text:str}', function (Context $ctx){
    $ctx->reply($ctx->params['text']);
});

$bot->run();
```

