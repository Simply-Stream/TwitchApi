<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications\Message as ChatMessage;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class ChannelChatMessageEvent extends Event
{
    use SerializesModels;

    /**
     * @param string      $broadcasterUserId           The broadcaster user ID.
     * @param string      $broadcasterUserLogin        The broadcaster display name.
     * @param string      $broadcasterUserName         The broadcaster login.
     * @param string      $chatterUserId               The user ID of the user that sent the message.
     * @param string      $chatterUserLogin            The user name of the user that sent the message.
     * @param string      $chatterUserName             The user login of the user that sent the message.
     * @param string      $messageId                   A UUID that identifies the message.
     * @param ChatMessage $message                     The structured chat message.
     * @param string      $messageType                 The type of message. Possible values:
     *                                                 - text
     *                                                 - channel_points_highlighted
     *                                                 - channel_points_sub_only
     *                                                 - user_intro
     * @param array       $badges                      List of chat badges.
     * @param string      $color                       The color of the user’s name in the chat room. This is a
     *                                                 hexadecimal RGB color code in the form, #. This tag may be empty
     *                                                 if it is never set.
     * @param Cheer|null  $cheer                       Optional. Metadata if this message is a cheer.
     * @param Reply|null  $reply                       Optional. Metadata if this message is a reply.
     * @param string|null $channelPointsCustomRewardId Optional. The ID of a channel points custom reward that was
     *                                                 redeemed.
     */
    public function __construct(
        private string $broadcasterUserId,
        private string $broadcasterUserLogin,
        private string $broadcasterUserName,
        private string $chatterUserId,
        private string $chatterUserLogin,
        private string $chatterUserName,
        private string $messageId,
        private ChatMessage $message,
        private string $messageType,
        private array $badges,
        private string $color,
        private ?Cheer $cheer,
        private ?Reply $reply,
        private ?string $channelPointsCustomRewardId
    ) {
    }

    public function getBroadcasterUserId(): string
    {
        return $this->broadcasterUserId;
    }

    public function getBroadcasterUserLogin(): string
    {
        return $this->broadcasterUserLogin;
    }

    public function getBroadcasterUserName(): string
    {
        return $this->broadcasterUserName;
    }

    public function getChatterUserId(): string
    {
        return $this->chatterUserId;
    }

    public function getChatterUserLogin(): string
    {
        return $this->chatterUserLogin;
    }

    public function getChatterUserName(): string
    {
        return $this->chatterUserName;
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function getMessage(): ChatMessage
    {
        return $this->message;
    }

    public function getMessageType(): string
    {
        return $this->messageType;
    }

    public function getBadges(): array
    {
        return $this->badges;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getCheer(): ?Cheer
    {
        return $this->cheer;
    }

    public function getReply(): ?Reply
    {
        return $this->reply;
    }

    public function getChannelPointsCustomRewardId(): ?string
    {
        return $this->channelPointsCustomRewardId;
    }
}
