<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class Chat extends Base
{

    public function id() : int
    {
        return $this->__data->id;
    }

    public function type() : string
    {
        return $this->__data->type;
    }

    public function title() : ?string
    {
        return $this->__data->title ?? null;
    }

    public function username() : ?string
    {
        return $this->__data->username ?? null;
    }

    public function firstName() : ?string
    {
        return $this->__data->first_name ?? null;
    }

    public function lastName() : ?string
    {
        return $this->__data->last_name ?? null;
    }

    public function allMembersAreAdministrators() : ?bool
    {
        return $this->__data->all_members_are_administrators ?? null;
    }

    public function photo() : ChatPhoto
    {
        return new ChatPhoto($this->__data->photo);
    }

    public function description() : ?string
    {
        return $this->__data->description ?? null;
}

    public function inviteLink() : ?string
    {
        return $this->__data->invite_link ?? null;
    }

    public function pinnedMessage() : ?Message
    {
        return isset($this->__data->pinned_message) ? new Message($this->__data->pinned_message) : null;
    }

    public function stickerSetName() : ?string
    {
        return $this->__data->sticker_set_name ?? null;
    }

    public function canSetStickerSet() : ?bool
    {
        return $this->__data->can_set_sticker_set ?? null;
    }
}