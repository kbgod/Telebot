<?php

namespace Telebot\Core;


use Telebot\Addons\Functions;
use Telebot\Addons\Keyboard;
use Telebot\Addons\MediaGroup;

class API
{

    public $settings = [
        'api_token'  => '',
        'base_url'   => 'https://api.telegram.org/',
        'username'   => '',
        'use_proxy'  => false,
        'run_type'   => 0,
        'hook_reply' => true,
        'debug_mode' => false,
        'timing'     => false,
        'log_mode'   => false,
        'log_path'   => __DIR__ . DIRECTORY_SEPARATOR . 'tb_log',
        'proxy'      => [
            'authorization' => '',
            'server'        => '',
            'proxy_type'    => '',
        ],
        'redbean_dsn'      => '',
        'redbean_user'     => '',
        'redbean_password' => ''
    ];

    private $WH_BL = [
        'getChat',
        'getChatAdministrators',
        'getChatMember',
        'getChatMembersCount',
        'getFile',
        'getFileLink',
        'getGameHighScores',
        'getMe',
        'getUserProfilePhotos',
        'getWebhookInfo'
    ];

    private $ch;
    private $webhookreply_used = false;

    public function __construct($settings)
    {
        $this->settings = array_replace_recursive($this->settings, $settings);
        $this->initCurl();
    }

    public function initCurl()
    {
        $this->ch = curl_init();
    }

    public function closeCurl()
    {
        curl_close($this->ch);
    }

    public function __destruct()
    {
        $this->closeCurl();
    }

    public function getApiUrl()
    {
        return trim($this->settings['base_url'], '/') . '/bot' . $this->settings['api_token'] . '/';
    }

    private function closeRequest($data)
    {
        if(function_exists('fastcgi_finish_request'))
        {
            echo $data;
            fastcgi_finish_request();
        }else{
            ob_start();
            header("Connection: close\r\n");
            header("Content-Type: application/json; charset=utf-8");
            echo $data;

            $size = ob_get_length();
            header("Content-Length: ". $size . "\r\n");
            ob_end_flush();
            flush();
        }
    }

    public function callMethod($method, $fields = [], $headers = [], $json = true)
    {
        if ($this->settings['run_type'] == 1 AND !$this->settings['debug_mode'] AND $this->settings['hook_reply'] AND !$this->webhookreply_used AND !in_array($method, $this->WH_BL)) {
            $this->webhookreply_used = true;
            $response['method'] = $method;
            $response = $response + $fields;
            $this->closeRequest(json_encode($response));
            return true;

        }

        curl_setopt($this->ch, CURLOPT_URL, $this->getApiUrl() . $method);
        if (is_array($headers) AND count($headers) > 0) curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        if ($this->settings['use_proxy']) {
            curl_setopt($this->ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($this->ch, CURLOPT_PROXY, $this->settings['proxy']['server']);
            curl_setopt($this->ch, CURLOPT_HTTPPROXYTUNNEL, true);
            curl_setopt($this->ch, CURLOPT_PROXYTYPE, $this->settings['proxy']['proxy_type']);
            if (mb_strlen($this->settings['proxy']['authorization']) > 0) {
                curl_setopt($this->ch, CURLOPT_PROXYUSERPWD, $this->settings['proxy']['authorization']);
            }

        }
        if($this->settings['timing']) Functions::ctime('callMethod');
        $response = curl_exec($this->ch);
        $this->processError($method, $response, $fields);
        if($this->settings['timing']) $this->trace('#[API] - Метод: \''.$method.'\' = '.Functions::ctime('callMethod'));
        if ($json) return json_decode($response);
        else return $response;
    }

    public function processError($method, $response, $fields = [])
    {
        $response = json_decode($response);
        if (!$response->ok) {
            $this->trace('#При выполнении метода: "' . $method . '" произошла ошибка!');
            $this->trace('#Код ошибки: ' . $response->error_code);
            $this->trace('#Описание ошибки: ' . $response->description);
            $this->trace('#Время вызова: ' . $this->datetime());
            $this->trace('#Данные: '.json_encode($fields));
        }
        return $response;
    }

    public function datetime()
    {
        return date('H:i:s m.d.Y');
    }

    public function trace($msg, $onlyDebug = false)
    {
        if ($this->settings['debug_mode']) {
            if (substr(PHP_OS, 0, 3) == 'WIN' and false == true) {
                if (!is_string($msg)) {
                    $text = unserialize(iconv('UTF-8', 'CP866', serialize($msg)));
                } else $text = iconv('UTF-8', 'CP866', $msg);
            } else $text = $msg;
            print_r($text);
            print_r(PHP_EOL);

        }
        if (!$onlyDebug AND $this->settings['log_mode']) {
            file_put_contents($this->settings['log_path'], $msg . PHP_EOL, FILE_APPEND);
        }
    }

    // Методы API

    public function getUpdates($params = [])
    {
        return $this->callMethod('getUpdates', $params);
    }

    public function sendMessage($params)
    {
        return $this->callMethod('sendMessage', $params);
    }

    public function getMe()
    {
        return $this->callMethod('getMe');
    }

    public function forwardMessage($params)
    {
        return $this->callMethod('forwardMessage', $params);
    }

    public function sendPhoto($params)
    {
        $headers = [];
        if (isset($params['photo']) AND $params['photo'] instanceof \CURLFile) {
            $this->webhookreply_used = true;
            $headers[] = 'Content-Type:multipart/form-data';
        }
        return $this->callMethod('sendPhoto', $params, $headers);
    }

    public function sendAudio($params)
    {
        $headers = [];
        if (isset($params['audio']) AND $params['audio'] instanceof \CURLFile) {
            $this->webhookreply_used = true;
            $headers[] = 'Content-Type:multipart/form-data';
        }
        return $this->callMethod('sendAudio', $params, $headers);
    }

    public function sendDocument($params)
    {
        $headers = [];
        if (isset($params['document']) AND $params['document'] instanceof \CURLFile) {
            $this->webhookreply_used = true;
            $headers[] = 'Content-Type:multipart/form-data';
        }
        return $this->callMethod('sendDocument', $params, $headers);
    }

    public function sendSticker($params)
    {
        $headers = [];
        if (isset($params['sticker']) AND $params['sticker'] instanceof \CURLFile) {
            $this->webhookreply_used = true;
            $headers[] = 'Content-Type:multipart/form-data';
        }
        return $this->callMethod('sendSticker', $params, $headers);
    }

    public function sendVideo($params)
    {
        $headers = [];
        if (isset($params['video']) AND $params['video'] instanceof \CURLFile) {
            $this->webhookreply_used = true;
            $headers[] = 'Content-Type:multipart/form-data';
        }
        return $this->callMethod('sendVideo', $params, $headers);
    }

    public function sendAnimation($params)
    {
        $headers = [];
        if (isset($params['animation']) AND $params['animation'] instanceof \CURLFile) {
            $this->webhookreply_used = true;
            $headers[] = 'Content-Type:multipart/form-data';
        }
        return $this->callMethod('sendAnimation', $params, $headers);
    }

    public function sendVoice($params)
    {
        $headers = [];
        if (isset($params['voice']) AND $params['voice'] instanceof \CURLFile) {
            $this->webhookreply_used = true;
            $headers[] = 'Content-Type:multipart/form-data';
        }
        return $this->callMethod('sendVoice', $params, $headers);
    }

    public function sendVideoNote($params)
    {
        $headers = [];
        if (isset($params['video_note']) AND $params['video_note'] instanceof \CURLFile) {
            $this->webhookreply_used = true;
            $headers[] = 'Content-Type:multipart/form-data';
        }
        return $this->callMethod('sendVideoNote', $params, $headers);
    }

    public function sendMediaGroup($params)
    {
        $headers[] = 'Content-Type:multipart/form-data';
        $this->webhookreply_used = true;
        if (isset($params['media']) and $params['media'] instanceof MediaGroup) $params['media']->build($params);
        return $this->callMethod('sendMediaGroup', $params, $headers);
    }

    public function sendLocation($params)
    {
        return $this->callMethod('sendLocation', $params);
    }

    public function editMessageLiveLocation($params)
    {
        return $this->callMethod('editMessageLiveLocation', $params);
    }

    public function stopMessageLiveLocation($params = [])
    {
        return $this->callMethod('stopMessageLiveLocation', $params);
    }

    public function sendVenue($params)
    {
        return $this->callMethod('sendVenue', $params);
    }

    public function sendContact($params)
    {
        return $this->callMethod('sendContact', $params);
    }

    public function sendChatAction($params)
    {
        return $this->callMethod('sendChatAction', $params);
    }

    public function getUserProfilePhotos($params)
    {
        return $this->callMethod('getUserProfilePhotos', $params);
    }

    public function getFile($params)
    {
        return $this->callMethod('getFile', $params);
    }

    public function kickChatMember($params)
    {
        return $this->callMethod('kickChatMember', $params);
    }

    public function unbanChatMember($params)
    {
        return $this->callMethod('unbanChatMember', $params);
    }

    public function restrictChatMember($params)
    {
        return $this->callMethod('restrictChatMember', $params);
    }

    public function promoteChatMember($params)
    {
        return $this->callMethod('promoteChatMember', $params);
    }

    public function exportChatInviteLink($params)
    {
        return $this->callMethod('exportChatInviteLink', $params);
    }

    public function setChatPhoto($params)
    {
        $headers = [];
        if (isset($params['photo']) AND $params['photo'] instanceof \CURLFile) {
            $this->webhookreply_used = true;
            $headers[] = 'Content-Type:multipart/form-data';
        }
        return $this->callMethod('setChatPhoto', $params, $headers);
    }

    public function deleteChatPhoto($params)
    {
        return $this->callMethod('deleteChatPhoto', $params);
    }

    public function setChatTitle($params)
    {
        return $this->callMethod('setChatTitle', $params);
    }

    public function setChatDescription($params)
    {
        return $this->callMethod('setChatDescription', $params);
    }

    public function pinChatMessage($params)
    {
        return $this->callMethod('pinChatMessage', $params);
    }

    public function unpinChatMessage($params)
    {
        return $this->callMethod('unpinChatMessage', $params);
    }

    public function leaveChat($params)
    {
        return $this->callMethod('leaveChat', $params);
    }

    public function getChat($params)
    {
        return $this->callMethod('getChat', $params);
    }

    public function getChatAdministrators($params)
    {
        return $this->callMethod('getChatAdministrators', $params);
    }

    public function getChatMembersCount($params)
    {
        return $this->callMethod('getChatMembersCount', $params);
    }

    public function getChatMember($params)
    {
        return $this->callMethod('getChatMember', $params);
    }

    public function setChatStickerSet($params)
    {
        return $this->callMethod('setChatStickerSet', $params);
    }

    public function deleteChatStickerSet($params)
    {
        return $this->callMethod('deleteChatStickerSet', $params);
    }

    public function answerCallbackQuery($params)
    {
        return $this->callMethod('answerCallbackQuery', $params);
    }

    public function editMessageText($params)
    {
        return $this->callMethod('editMessageText', $params);
    }

    public function editMessageCaption($params = [])
    {
        return $this->callMethod('editMessageCaption', $params);
    }

    public function editMessageMedia($params)
    {
        return $this->callMethod('editMessageMedia', $params);
    }

    public function editMessageReplyMarkup($params = [])
    {
        return $this->callMethod('editMessageReplyMarkup', $params);
    }

    public function deleteMessage($params)
    {
        return $this->callMethod('deleteMessage', $params);
    }

    public function answerInlineQuery($params)
    {
        return $this->callMethod('answerInlineQuery', $params);
    }

    public function sendInvoice($params)
    {
        return $this->callMethod('sendInvoice', $params);
    }

    public function answerShippingQuery($params)
    {
        return $this->callMethod('answerShippingQuery', $params);
    }

    public function answerPreCheckoutQuery($params)
    {
        return $this->callMethod('answerPreCheckoutQuery', $params);
    }

    public function setPassportDataErrors($params)
    {
        return $this->callMethod('setPassportDataErrors', $params);
    }

    public function sendGame($params)
    {
        return $this->callMethod('sendGame', $params);
    }

    public function setGameScore($params)
    {
        return $this->callMethod('setGameScore', $params);
    }

    public function getGameHighScores($params)
    {
        return $this->callMethod('getGameHighScores', $params);
    }
}