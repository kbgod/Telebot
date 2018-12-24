<?php

namespace Telebot\Types;


use Telebot\Types\Base\Base;

class ChatMember extends Base
{
    public function user() : ?User
    {
        return isset($this->__data->user) ? new User($this->__data->user) : null;
    }

    public function status() : string
    {
        return $this->__data->status;
    }

    public function untilDate() : ?int
    {
        return $this->__data->until_date ?? null;
    }

    public function canBeEdited() : ?bool
    {
        return $this->__data->can_be_edited ?? null;
    }

    public function canChangeInfo() : ?bool
    {
        return $this->__data->can_change_info ?? null;
    }

    public function canPostMessages() : ?bool
    {
        return $this->__data->can_post_messages ?? null;
    }

    public function canEditMessages() : ?bool
    {
        return $this->__data->can_edit_messages ?? null;
    }

    public function canDeleteMessages() : ?bool
    {
        return $this->__data->can_delete_messages ?? null;
    }

    public function canInviteUsers() : ?bool
    {
        return $this->__data->can_invite_users ?? null;
    }

    public function canRestrictMembers() : ?bool
    {
        return $this->__data->can_restrict_members ?? null;
    }

    public function canPinMessages() : ?bool
    {
        return $this->__data->can_pin_messages ?? null;
    }

    public function canPromoteMembers() : ?bool
    {
        return $this->__data->can_promote_members ?? null;
    }

    public function canSendMessages() : ?bool
    {
        return $this->__data->can_send_messages ?? null;
    }

    public function canSendMediaMessages() : ?bool
    {
        return $this->__data->can_send_media_messages ?? null;
    }

    public function canSendOtherMessages() : ?bool
    {
        return $this->__data->can_send_other_messages ?? null;
    }

    public function canAddWebPagePreviews() : ?bool
    {
        return $this->__data->can_add_web_page_previews ?? null;
    }
}