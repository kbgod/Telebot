<?php

namespace Telebot\Core;

use Telebot\Interfaces\UserInterface;
use Telebot\Types\CallbackQuery;
use Telebot\Types\Chat;
use Telebot\Types\ChosenInlineResult;
use Telebot\Types\InlineQuery;
use Telebot\Types\Message;
use Telebot\Types\PreCheckoutQuery;
use Telebot\Types\ShippingQuery;
use Telebot\Types\Sticker;
use Telebot\Types\Update;
use Telebot\Types\User;

class Context
{

    /*public $updateTypes = [
        'callback_query',
        'channel_post',
        'chosen_inline_result',
        'edited_channel_post',
        'edited_message',
        'inline_query',
        'shipping_query',
        'pre_checkout_query',
        'message'
    ];*/

    /*public $messageSubTypes = [
        'voice',
        'video_note',
        'video',
        'animation',
        'venue',
        'text',
        'supergroup_chat_created',
        'successful_payment',
        'sticker',
        'pinned_message',
        'photo',
        'new_chat_title',
        'new_chat_photo',
        'new_chat_members',
        'migrate_to_chat_id',
        'migrate_from_chat_id',
        'location',
        'left_chat_member',
        'invoice',
        'group_chat_created',
        'game',
        'document',
        'delete_chat_photo',
        'contact',
        'channel_chat_created',
        'audio',
        'connected_website',
        'passport_data'
    ];*/

    public $updateType;
    public $updateSubType;
    public $update;
    public $api;
    public $__user;
    public $params;
    private $scenes;

    public function __construct(Update $update, API $api, $scenes)
    {
        $this->update = $update;
        $this->api = $api;
        $this->scenes = $scenes;
    }

    public function setUserControl(UserInterface $control)
    {
        $this->__user = $control;
    }

    public function usedControl()
    {
        return ($this->__user instanceof UserInterface) ? true : false;
    }

    public function user(): UserInterface
    {
        return $this->__user;
    }

    public function getState()
    {
        return $this->user()->getState();
    }

    public function setState($state)
    {
        $this->user()->setState($state);
        return $this;
    }

    public function enter($sceneName)
    {
        $this->setState($sceneName);
        if (is_callable($this->scenes[$sceneName]['enter'])) $this->scenes[$sceneName]['enter']($this);
        else {
            $this->api->trace('[WARNING] Scene "'.$sceneName.'" not found!');
        }
    }

    public function leave()
    {
        if (is_callable($this->scenes[$this->getState()]['leave'])) $this->scenes[$this->getState()]['leave']($this);
        $this->setState('');
    }

    public function getMessage() : ?Message
    {
        if($this->update->exists('message')) return $this->update->message();
        elseif ($this->update->exists('edited_message')) return $this->update->editedMessage();
        elseif (
            $this->update->exists('callback_query') and
            $this->update->callbackQuery()->exists('message')
        ) return $this->update->callbackQuery()->message();
        elseif ($this->update->exists('channel_post')) $this->update->channelPost();
        elseif ($this->update->exists('edited_channel_post')) $this->update->editedChannelPost();
        else return null;
    }

    public function getFrom() : ?User
    {
        if ($this->update->exists('message')) return $this->update->message()->from();
        elseif ($this->update->exists('edited_message'))        return $this->update->editedMessage()->from();
        elseif ($this->update->exists('callback_query'))        return $this->update->callbackQuery()->from();
        elseif ($this->update->exists('inline_query'))          return $this->update->inlineQuery()->from();
        elseif ($this->update->exists('channel_post'))          return $this->update->channelPost()->from();
        elseif ($this->update->exists('edited_channel_post'))   return $this->update->editedChannelPost()->from();
        elseif ($this->update->exists('shipping_query'))        return $this->update->shippingQuery()->from();
        elseif ($this->update->exists('pre_checkout_query'))    return $this->update->preCheckoutQuery()->from();
        elseif ($this->update->exists('chosen_inline_result'))  return $this->update->chosenInlineResult()->from();
        else return null;
    }

    public function getChat() : ?Chat
    {
        return $this->getMessage()->chat() ?? null;
    }

    public function getText(): string
    {
        return $this->getMessage()->text() ?? '';
    }

    public function getLowerCaseText(): string
    {
        return mb_strtolower($this->getText());
    }

    public function getSticker(): ?Sticker
    {
        return !is_null($this->getMessage()) ? ($this->getMessage()->exists('sticker') ? $this->getMessage()->sticker() : null) : null;
    }

    public function getChatID(): int
    {
        return $this->getChat()->id();
    }

    public function getMessageID(): int
    {
        return $this->getMessage()->messageId();
    }

    public function getCallbackID(): int
    {
        return $this->callbackQuery()->id();
    }

    public function getUserID(): int
    {
        return $this->getFrom()->id();
    }

    public function getUsername(): string
    {
        return $this->getFrom()->username();
    }

    public function getFromIsBot() : bool
    {
        return $this->getFrom()->isBot();
    }

    public function getFirstName(): string
    {
        return $this->getFrom()->firstName();
    }

    public function getLastName(): string
    {
        return $this->getFrom()->lastName();
    }

    public function getFullName(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function getLangCode(): string
    {
        return $this->getFrom()->languageCode();
    }

    public function getInlineQueryID(): string
    {
        return $this->inlineQuery()->id();
    }

    public function getInlineMessID()
    {
        return $this->chosenInlineResult()->inlineMessageId();
    }

    public function editedMessage() : ?Message
    {
        return $this->update->editedMessage();
    }

    public function inlineQuery() : ?InlineQuery
    {
        return $this->update->inlineQuery();
    }

    public function shippingQuery() : ?ShippingQuery
    {
        return $this->update->shippingQuery();
    }

    public function preCheckoutQuery() : ?PreCheckoutQuery
    {
        return $this->update->preCheckoutQuery();
    }

    public function chosenInlineResult() : ?ChosenInlineResult
    {
        return $this->update->chosenInlineResult();
    }

    public function channelPost() : ?Message
    {
        return $this->update->channelPost();
    }

    public function editedChannelPost() : ?Message
    {
        return $this->update->editedChannelPost();
    }

    public function callbackQuery() : ?CallbackQuery
    {
        return $this->update->callbackQuery();
    }


    // Кастомные методы
    public function reply($text, $keyboard = null, $reply_mode = false, $options = [])
    {
        $fields = ['chat_id' => $this->getChatID(), 'text' => $text, 'reply_markup' => (string)$keyboard];
        if ($reply_mode) $fields['reply_to_message_id'] = $this->getMessageID();
        $fields = $fields + $options;
        return $this->api->sendMessage($fields);
    }

    public function replyHTML($text, $keyboard = null, $reply_mode = false, $options = [])
    {
        $options['parse_mode'] = 'HTML';
        return $this->reply($text, $keyboard, $reply_mode, $options);
    }

    public function replyMarkdown($text, $keyboard = null, $reply_mode = false, $options = [])
    {
        $options['parse_mode'] = 'Markdown';
        return $this->reply($text, $keyboard, $reply_mode, $options);
    }

    public function replyPhoto($photo, $caption = null, $keyboard = null, $reply_mode = false, $options = [])
    {
        $fields = ['chat_id' => $this->getChatID(), 'photo' => $photo, 'caption' => $caption, 'reply_markup' => (string)$keyboard];
        if ($reply_mode) $fields['reply_to_message_id'] = $this->getMessageID();
        $fields = $fields + $options;
        $this->api->sendPhoto($fields);
    }

    public function replyDocument($document, $caption = null, $keyboard = null, $reply_mode = false, $options = [])
    {
        $fields = ['chat_id' => $this->getChatID(), 'document' => $document, 'caption' => $caption, 'reply_markup' => (string)$keyboard];
        if ($reply_mode) $fields['reply_to_message_id'] = $this->getMessageID();
        $fields = $fields + $options;
        $this->api->sendDocument($fields);
    }

    public function replyAudio($audio, $caption = null, $keyboard = null, $reply_mode = false, $options = [])
    {
        $fields = ['chat_id' => $this->getChatID(), 'audio' => $audio, 'caption' => $caption, 'reply_markup' => (string)$keyboard];
        if ($reply_mode) $fields['reply_to_message_id'] = $this->getMessageID();
        $fields = $fields + $options;
        $this->api->sendAudio($fields);
    }

    public function replyVideo($video, $keyboard = null, $reply_mode = false, $options = [])
    {
        $fields = ['chat_id' => $this->getChatID(), 'video' => $video, 'reply_markup' => (string)$keyboard];
        if ($reply_mode) $fields['reply_to_message_id'] = $this->getMessageID();
        $fields = $fields + $options;
        $this->api->sendVideo($fields);
    }

    public function replyMediaGroup($media, $reply_mode = false, $disable_notification = false)
    {
        $fields = ['chat_id' => $this->getChatID(), 'media' => $media, 'disable_notification' => $disable_notification];
        if ($reply_mode) $fields['reply_to_message_id'] = $this->getMessageID();
        return $this->api->sendMediaGroup($fields);
    }

    public function editActTxt($text, $keyboard = null, $options = [])
    {
        $fields = ['chat_id' => $this->getChatID(), 'message_id' => $this->getMessageID(), 'text' => $text, 'reply_markup' => (string)$keyboard];
        $fields = $fields + $options;
        $this->api->editMessageText($fields);

    }

    public function editActTxtHTML($text, $keyboard = null, $disable_web_page_preview = false)
    {
        $this->api->editMessageText(['chat_id' => $this->getChatID(), 'message_id' => $this->getMessageID(), 'text' => $text, 'reply_markup' => (string)$keyboard, 'parse_mode' => 'HTML', 'disable_web_page_preview' => $disable_web_page_preview]);
    }

    public function editActTxtMarkdown($text, $keyboard = null, $disable_web_page_preview = false)
    {
        $this->api->editMessageText(['chat_id' => $this->getChatID(), 'message_id' => $this->getMessageID(), 'text' => $text, 'reply_markup' => (string)$keyboard, 'parse_mode' => 'Markdown', 'disable_web_page_preview' => $disable_web_page_preview]);
    }

    public function ansCallback($text = null, $alert = false, $url = null, $cache = 0)
    {
        return $this->api->answerCallbackQuery(['callback_query_id' => $this->getCallbackID(), 'text' => $text, 'show_alert' => $alert, 'url' => $url, 'cache_time' => $cache]);
    }

    public function getFile($fileID)
    {
        return $this->api->getFile(['file_id' => $fileID]);
    }

    public function getFileLink($fileID)
    {
        $response = $this->getFile($fileID);
        return (isset($response->result->file_path)) ? 'https://api.telegram.org/file/bot' . $this->api->settings['api_token'] . '/' . $response->result->file_path : $response;
    }

    public function replyChatAction($act)
    {
        return $this->api->sendChatAction(['chat_id' => $this->getChatID(), 'action' => $act]);
    }

    // Устаревшие кастомные методы
    public function sMessage($chatID, $message, $keyboard = null, $parseMode = false, $disableWebPreview = false, $replyID = null)
    {
        $fields = ['chat_id' => $chatID, 'text' => $message, 'disable_web_page_preview' => $disableWebPreview, 'reply_markup' => (string)$keyboard];
        if ($parseMode) $fields['parse_mode'] = $parseMode;
        if ($replyID) $fields['reply_to_message_id'] = $replyID;
        return $this->api->sendMessage($fields);
    }

    public function fMessage($chatID, $fromID, $messID, $keyboard = null, $disableNotification = false)
    {
        $fields = ['chat_id' => $chatID, 'from_chat_id' => $fromID, 'message_id' => $messID, 'disable_notification' => $disableNotification, 'reply_markup' => (string)$keyboard];
        return $this->api->forwardMessage($fields);

    }

    public function sDocument($chatID, $document, $caption = null, $disableNotification = false, $keyboard = null, $replyID = null)
    {
        $fields = ['chat_id' => $chatID, 'document' => $document, 'disable_notification' => $disableNotification, 'caption' => $caption, 'reply_markup' => (string)$keyboard];
        if ($replyID) $fields['reply_to_message_id'] = $replyID;
        return $this->api->sendDocument($fields);

    }

    public function sSticker($chatID, $sticker, $disableNotification = false, $keyboard = null, $replyID = null)
    {
        $fields = ['chat_id' => $chatID, 'sticker' => $sticker, 'disable_notification' => $disableNotification, 'reply_markup' => (string)$keyboard];
        if ($replyID) $fields['reply_to_message_id'] = $replyID;
        return $this->api->sendSticker($fields);

    }

    public function sMediaGroup($chatID, $media, $disableNotification = false, $replyID = null)
    {
        $fields = ['chat_id' => $chatID, 'media' => $media, 'disable_notification' => $disableNotification];
        if ($replyID) $fields['reply_to_message_id'] = $replyID;
        return $this->api->sendMediaGroup($fields);

    }

    public function sPhoto($chatID, $photo, $caption = null, $keyboard = null, $disableNotification = false, $replyID = null)
    {
        $fields = ['chat_id' => $chatID, 'photo' => $photo, 'caption' => $caption, 'disable_notification' => $disableNotification, 'reply_markup' => (string)$keyboard];
        if ($replyID) $fields['reply_to_message_id'] = $replyID;
        return $this->api->sendPhoto($fields);
    }

    public function sAudio($chatID, $audio, $caption = null, $duration = null, $performer = null, $title = null, $disableNotification = false, $keyboard = null, $replyID = null)
    {
        $fields = ['chat_id' => $chatID, 'audio' => $audio, 'caption' => $caption, 'duration' => $duration, 'performer' => $performer, 'title' => $title, 'disable_notification' => $disableNotification, 'reply_markup' => (string)$keyboard];
        if ($replyID) $fields['reply_to_message_id'] = $replyID;
        return $this->api->sendAudio($fields);
    }

    public function sVoice($chatID, $voice, $caption = null, $duration = null, $disableNotification = false, $keyboard = null, $replyID = null)
    {
        $fields = ['chat_id' => $chatID, 'voice' => $voice, 'caption' => $caption, 'duration' => $duration, 'disable_notification' => $disableNotification, 'reply_markup' => (string)$keyboard];
        if ($replyID) $fields['reply_to_message_id'] = $replyID;
        return $this->api->sendVoice($fields);
    }

    public function sVideo($chatID, $video, $caption = null, $duration = null, $width = null, $height = null, $disableNotification = false, $keyboard = null, $replyID = null)
    {
        $fields = ['chat_id' => $chatID, 'video' => $video, 'caption' => $caption, 'duration' => $duration, 'width' => $width, 'height' => $height, 'disable_notification' => $disableNotification, 'reply_markup' => (string)$keyboard];
        if ($replyID) $fields['reply_to_message_id'] = $replyID;
        return $this->api->sendVideo($fields);
    }

    public function sVideoNote($chatID, $video, $duration = null, $length = null, $disableNotification = false, $keyboard = null, $replyID = null)
    {
        $fields = ['chat_id' => $chatID, 'video_note' => $video, 'disable_notification' => $disableNotification, 'duration' => $duration, 'length' => $length, 'reply_markup' => (string)$keyboard];
        if ($replyID) $fields['reply_to_message_id'] = $replyID;
        return $this->api->sendVideoNote($fields);
    }

    public function sContact($chatID, $phone, $first_name, $last_name = null, $disableNotification = false, $keyboard = null, $replyID = null)
    {
        $fields = ['chat_id' => $chatID, 'phone_number' => $phone, 'first_name' => $first_name, 'last_name' => $last_name, 'disable_notification' => $disableNotification, 'reply_markup' => (string)$keyboard];
        if ($replyID) $fields['reply_to_message_id'] = $replyID;
        return $this->api->sendContact($fields);
    }

    public function sLocation($chatID, $latitude, $longitude, $livePeriod = null, $disableNotification = false, $keyboard = null, $replyID = null)
    {
        $fields = ['chat_id' => $chatID, 'latitude' => $latitude, 'longitude' => $longitude, 'live_period' => $livePeriod, 'disable_notification' => $disableNotification, 'reply_markup' => (string)$keyboard];
        if ($replyID) $fields['reply_to_message_id'] = $replyID;
        return $this->api->sendLocation($fields);

    }

    public function editMessageLiveLocation($chatID, $messID, $latitude, $longitude, $keyboard = null)
    {
        $fields = ['chat_id' => $chatID, 'message_id' => $messID, 'latitude' => $latitude, 'longitude' => $longitude, 'reply_markup' => (string)$keyboard];
        return $this->api->editMessageLiveLocation($fields);
    }

    public function editInlineLiveLocation($inlineMessID, $latitude, $longitude, $keyboard = null)
    {
        $fields = ['inline_message_id' => $inlineMessID, 'latitude' => $latitude, 'longitude' => $longitude, 'reply_markup' => (string)$keyboard];
        return $this->api->editMessageLiveLocation($fields);
    }

    public function stopMessageLiveLocation($chatID, $messID, $keyboard = null)
    {
        $fields = ['chat_id' => $chatID, 'message_id' => $messID, 'reply_markup' => (string)$keyboard];
        return $this->api->stopMessageLiveLocation($fields);
    }

    public function stopInlineLiveLocation($inlineMessID, $keyboard = null)
    {
        $fields = ['inline_message_id' => $inlineMessID, 'reply_markup' => (string)$keyboard];
        return $this->api->stopMessageLiveLocation($fields);
    }

    public function sVenue($chatID, $latitude, $longitude, $title = null, $address = null, $foursquare = null, $disableNotification = false, $keyboard = null, $replyID = null)
    {
        $fields = ['chat_id' => $chatID, 'latitude' => $latitude, 'longitude' => $longitude, 'title' => $title, 'address' => $address, 'foursquare_id' => $foursquare, 'disable_notification' => $disableNotification, 'reply_markup' => (string)$keyboard];
        if ($replyID) $fields['reply_to_message_id'] = $replyID;
        return $this->api->sendVenue($fields);

    }

    public function sChatAction($chatID, $act)
    {
        return $this->api->sendChatAction(['chat_id' => $chatID, 'action' => $act]);
    }

    public function getUserProfilePhotos($userID, $offset = null, $limit = 100)
    {
        return $this->api->getUserProfilePhotos(['user_id' => $userID, 'limit' => $limit, 'offset' => $offset]);
    }

    public function kickChatMember($chatID, $userID)
    {
        return $this->api->kickChatMember(['chat_id' => $chatID, 'user_id' => $userID]);
    }

    public function unbanChatMember($chatID, $userID)
    {
        return $this->api->unbanChatMember(['chat_id' => $chatID, 'user_id' => $userID]);
    }

    public function restrictChatMember($chatID, $userID, $date, $canSendMessage = false, $canSendMedia = false, $canSendOther = false, $canAddWebPrev = false)
    {
        return $this->api->restrictChatMember([
            'chat_id' => $chatID,
            'user_id' => $userID,
            'until_date' => $date,
            'can_send_messages' => $canSendMessage,
            'can_send_media_messages' => $canSendMedia,
            'can_send_other_messages' => $canSendOther,
            'can_add_web_page_previews' => $canAddWebPrev
        ]);
    }

    public function promoteChatMember(
        $chatID,
        $userID,
        $canChangeInfo = false,
        $canPostMessages = false,
        $canEditMessages = false,
        $canDeleteMessages = false,
        $canInviteUsers = false,
        $canRestrictMembers = false,
        $canPinMessages = false,
        $canPromoteMembers = false
    )
    {
        return $this->api->promoteChatMember([
            'chat_id' => $chatID,
            'user_id' => $userID,
            'can_change_info' => $canChangeInfo,
            'can_post_messages' => $canPostMessages,
            'can_edit_messages' => $canEditMessages,
            'can_delete_messages' => $canDeleteMessages,
            'can_invite_users' => $canInviteUsers,
            'can_restrict_members' => $canRestrictMembers,
            'can_pin_messages' => $canPinMessages,
            'can_promote_members' => $canPromoteMembers
        ]);

    }

    public function exportChatInviteLink($chatID)
    {
        return $this->api->exportChatInviteLink(['chat_id' => $chatID]);
    }

    public function setChatPhoto($chatID, $photo)
    {
        return $this->api->setChatPhoto(['chat_id' => $chatID, 'photo' => $photo]);
    }

    public function deleteChatPhoto($chatID)
    {
        return $this->api->deleteChatPhoto(['chat_id' => $chatID]);
    }

    public function setChatTitle($chatID, $title)
    {
        return $this->api->setChatTitle(['chat_id' => $chatID, 'title' => $title]);
    }

    public function setChatDescription($chatID, $description)
    {
        return $this->api->setChatDescription(['chat_id' => $chatID, 'description' => $description]);
    }

    public function pinChatMessage($chatID, $messID, $disableNotification = false)
    {
        return $this->api->pinChatMessage(['chat_id' => $chatID, 'message_id' => $messID, 'disable_notification' => $disableNotification]);
    }

    public function unpinChatMessage($chatID)
    {
        return $this->api->unpinChatMessage(['chat_id' => $chatID]);
    }

    public function leaveChat($chatID)
    {
        return $this->api->leaveChat(['chat_id' => $chatID]);
    }

    public function getChatMethod($chatID)
    {
        return $this->api->getChat(['chat_id' => $chatID]);
    }

    public function getChatAdministrators($chatID)
    {
        return $this->api->getChatAdministrators(['chat_id' => $chatID]);
    }

    public function getChatMembersCount($chatID)
    {
        return $this->api->getChatMembersCount(['chat_id' => $chatID]);
    }

    public function getChatMember($chatID, $userID)
    {
        return $this->api->getChatMember(['chat_id' => $chatID, 'user_id' => $userID]);
    }

    public function editMessageText($chatID, $messID, $text, $parseMode = false, $disableWebPreview = false, $keyboard = null)
    {
        $fields = [
            'chat_id' => $chatID,
            'message_id' => $messID,
            'text' => $text,
            'parse_mode' => $parseMode,
            'disable_web_page_preview' => $disableWebPreview,
            'reply_markup' => (string)$keyboard
        ];
        $this->api->editMessageText($fields);

    }

    public function editInlineText($inlineMessID, $text, $parseMode = false, $disableWebPreview = false, $keyboard = null)
    {
        $fields = [
            'inline_message_id' => $inlineMessID,
            'text' => $text,
            'parse_mode' => $parseMode,
            'disable_web_page_preview' => $disableWebPreview,
            'reply_markup' => (string)$keyboard
        ];
        $this->api->editMessageText($fields);

    }

    public function editMessageCaption($chatID, $messID, $caption, $keyboard = null)
    {
        $fields = [
            'chat_id' => $chatID,
            'message_id' => $messID,
            'caption' => $caption,
            'reply_markup' => (string)$keyboard
        ];
        $this->api->editMessageCaption($fields);
    }

    public function editInlineCaption($inlineMessID, $caption, $keyboard = null)
    {
        $fields = [
            'inline_message_id' => $inlineMessID,
            'caption' => $caption,
            'reply_markup' => (string)$keyboard
        ];
        $this->api->editMessageCaption($fields);
    }

    public function editKeyboard($chatID, $messID, $keyboard)
    {
        return $this->api->editMessageReplyMarkup(['chat_id' => $chatID, 'message_id' => $messID, 'reply_markup' => (string)$keyboard]);
    }

    public function delMessage($chatID, $messID)
    {
        return $this->api->deleteMessage(['chat_id' => $chatID, 'message_id' => $messID]);
    }

    public function sInvoice(
        $chatID,
        $title,
        $description,
        $payload,
        $token,
        $start_parameter,
        $currency,
        $prices,
        $photo = null,
        $phSize = null,
        $phWidth = null,
        $phHeight = null,
        $needName = false,
        $needNumber = false,
        $needEmail = false,
        $needAddress = false,
        $isFlexible = false,
        $disableNotification = false,
        $keyboard = false,
        $replyID = null
    )
    {
        $fields = [
            'chat_id' => $chatID,
            'title' => $title,
            'description' => $description,
            'payload' => $payload,
            'provider_token' => $token,
            'start_parameter' => $start_parameter,
            'currency' => $currency,
            'prices' => $prices,
            'photo_size' => $phSize,
            'photo_width' => $phWidth,
            'photo_height' => $phHeight,
            'need_name' => $needName,
            'need_phone_number' => $needNumber,
            'need_email' => $needEmail,
            'need_shipping_address' => $needAddress,
            'is_flexible' => $isFlexible,
            'disable_notification' => $disableNotification,
            'photo_url' => $photo,
            'reply_markup' => (string)$keyboard
        ];
        if ($replyID) $fields['reply_to_message_id'] = $replyID;
        return $this->api->sendInvoice($fields);
    }

    public function ansPreCheckoutQuery($id, $ok = false, $errorMessage = null)
    {
        return $this->api->answerPreCheckoutQuery([
            'pre_checkout_query_id' => $id,
            'ok' => $ok,
            'error_message' => $errorMessage
        ]);
    }

    public function answerShippingQuery($shippingID, $ok = false, $shippingOptions = null, $errorMessage = null)
    {
        return $this->api->answerShippingQuery([
            'shipping_query_id' => $shippingID,
            'ok' => $ok,
            'shipping_options' => $shippingOptions,
            'error_message' => $errorMessage
        ]);
    }

    public function ansInlineQuery($id, $results, $options = [])
    {
        $fields = ['inline_query_id' => $id, 'results' => (string) $results];
        $fields = $fields+$options;
        return $this->api->answerInlineQuery($fields);
    }
}