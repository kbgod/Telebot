<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class Message extends Base
{

    public function messageId() : int
    {
        return $this->__data->message_id;
    }

    public function from() : ?User
    {
        return isset($this->__data->from) ? new User($this->__data->from) : null;
    }

    public function date() : int
    {
        return $this->__data->date;
    }

    public function chat() : Chat
    {
        return new Chat($this->__data->chat);
    }

    public function forwardFrom() : ?User
    {
        return isset($this->__data->forward_from) ? new User($this->__data->forward_from) : null;
    }

    public function forwardFromChat() : ?Chat
    {
        return isset($this->__data->forward_from_chat) ? new Chat($this->__data->forward_from_chat) : null;
    }

    public function forwardFromMessageId() : ?int
    {
        return $this->__data->forward_from_message_id ?? null;
    }

    public function forwardSignature() : ?string
    {
        return $this->__data->forward_signature ?? null;
    }

    public function forwardDate() : ?int
    {
        return $this->__data->forward_date ?? null;
    }

    public function replyToMessage() : ?Message
    {
        return isset($this->__data->reply_to_message) ? new Message($this->__data->reply_to_message) : null;
    }

    public function editDate() : ?int
    {
        return $this->__data->edit_date ?? null;
    }

    public function mediaGroupId() : ?string
    {
        return $this->__data->media_group_id ?? null;
    }

    public function authorSignature() : ?string
    {
        return $this->__data->author_signature ?? null;
    }

    public function text() : ?string
    {
        return $this->__data->text ?? null;
    }

    /**
     * @return MessageEntity[]
     */
    public function entities() : ?array
    {
        if(isset($this->__data->entities)) {
            foreach ($this->__data->entities as $key => $entity) {
                $this->__data->entities[$key] = new MessageEntity($entity);
            }
            return $this->__data->entities;
        } else return null;
    }


    /**
     * @return MessageEntity[]
     */
    public function caption_entities() : ?array
    {
        if(isset($this->__data->caption_entities)) {
            foreach ($this->__data->caption_entities as $key => $entity) {
                $this->__data->caption_entities[$key] = new MessageEntity($entity);
            }
            return $this->__data->caption_entities;
        } else return null;
    }

    public function audio() : ?Audio
    {
        return isset($this->__data->audio) ? new Audio($this->__data->audio) : null;
    }

    public function document() : ?Document
    {
        return isset($this->__data->document) ? new Document($this->__data->document) : null;
    }

    public function animation() : ?Animation
    {
        return isset($this->__data->animation) ? new Animation($this->__data->animation) : null;
    }

    public function game() : ?Game
    {
        return isset($this->__data->animation) ? new Game($this->__data->animation) : null;
    }

    /**
     * @return PhotoSize[]
     */
    public function photo() : ?array
    {
        if(isset($this->__data->photo)) {
            foreach ($this->__data->photo as $key => $photo) {
                $this->__data->photo[$key] = new PhotoSize($photo);
            }
            return $this->__data->photo;
        } else return null;
    }

    public function sticker() : ?Sticker
    {
        return isset($this->__data->sticker) ? new Sticker($this->__data->sticker) : null;
    }

    public function video() : ?Video
    {
        return isset($this->__data->video) ? new Video($this->__data->video) : null;
    }

    public function voice() : ?Voice
    {
        return isset($this->__data->voice) ? new Voice($this->__data->voice) : null;
    }

    public function videoNote() : ?VideoNote
    {
        return isset($this->__data->video_note) ? new VideoNote($this->__data->video_note) : null;
    }

    public function caption() : ?string
    {
        return $this->__data->caption ?? null;
    }

    public function contact() : ?Contact
    {
        return isset($this->__data->contact) ? new Contact($this->__data->contact) : null;
    }

    public function location() : ?Location
    {
        return isset($this->__data->location) ? new Location($this->__data->location) : null;
    }

    public function venue() : ?Venue
    {
        return isset($this->__data->venue) ? new Venue($this->__data->venue) : null;
    }

    /**
     * @return User[]
     */
    public function newChatMembers() : ?array
    {
        if(isset($this->__data->new_chat_members)) {
            foreach ($this->__data->new_chat_members as $key => $user) {
                $this->__data->new_chat_members[$key] = new User($user);
            }
            return $this->__data->new_chat_members;
        } else return null;
    }

    public function leftChatMember() : ?User
    {
        return isset($this->__data->left_chat_member) ? new User($this->__data->left_chat_member) : null;
    }

    public function newChatTitle() : ?string
    {
        return $this->__data->new_chat_title ?? null;
    }

    /**
     * @return PhotoSize[]
     */
    public function newChatPhoto() : ?array
    {
        if(isset($this->__data->new_chat_photo)) {
            foreach ($this->__data->new_chat_photo as $key => $photo) {
                $this->__data->new_chat_photo[$key] = new PhotoSize($photo);
            }
            return $this->__data->new_chat_photo;
        } else return null;
    }

    public function deleteChatPhoto() : ?bool
    {
        return $this->__data->delete_chat_photo ?? null;
    }

    public function groupChatCreated() : ?bool
    {
        return $this->__data->group_chat_created ?? null;
    }

    public function supergroupChatCreated() : ?bool
    {
        return $this->__data->supergroup_chat_created ?? null;
    }

    public function channelChatCreated() : ?bool
    {
        return $this->__data->channel_chat_created ?? null;
    }

    public function migrateToChatId() : ?int
    {
        return $this->__data->migrate_to_chat_id ?? null;
    }

    public function migrateFromChatId() : ?int
    {
        return $this->__data->migrate_from_chat_id ?? null;
    }

    public function pinnedMessage() : ?Message
    {
        return isset($this->__data->pinned_message) ? new Message($this->__data->pinned_message) : null;
    }

    public function invoice() : ?Invoice
    {
        return isset($this->__data->invoice) ? new Invoice($this->__data->invoice) : null;
    }

    public function successfulPayment() : ?SuccessfulPayment
    {
        return isset($this->__data->successful_payment) ? new SuccessfulPayment($this->__data->successful_payment) : null;
    }

    public function connectedWebsite() : ?string
    {
        return $this->__data->connected_website ?? null;
    }

    public function passportData() : ?PassportData
    {
        return isset($this->__data->passport_data) ? new PassportData($this->__data->passport_data) : null;
    }

}