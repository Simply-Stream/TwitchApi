<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelChatMessageCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Shared\Message as ChatMessage;

#[EventSubSubscription(type: 'channel.chat.message', version: '1', condition: ChannelChatMessageCondition::class)]
final readonly class ChannelChatMessageEvent implements EventInterface
{
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
     * @param string|null $channelPointsAnimationId    Optional. Not documented by Twitch, but present in request
     *
     * @param string|null $sourceBroadcasterUserId     Optional. The
     *                                                 broadcaster user ID of the channel the message was sent from. Is
     *                                                 null when the message happens in the same channel as the
     *                                                 broadcaster. Is not null when in a shared chat session, and the
     *                                                 action happens in the channel of a participant other than the
     *                                                 broadcaster.
     * @param string|null $sourceBroadcasterUserName   Optional. The user
     *                                                 name of the broadcaster of the channel the message was sent
     *                                                 from. Is null when the message happens in the same channel as
     *                                                 the broadcaster. Is not null when in a shared chat session, and
     *                                                 the action happens in the channel of a participant other than
     *                                                 the broadcaster.
     * @param string|null $sourceBroadcasterUserLogin  Optional. The
     *                                                 login of the broadcaster of the channel the message was sent
     *                                                 from. Is null when the message happens in the same channel as
     *                                                 the broadcaster. Is not null when in a shared chat session, and
     *                                                 the action happens in the channel of a participant other than
     *                                                 the broadcaster.
     * @param string|null $sourceMessageId             Optional. The UUID that
     *                                                 identifies the source message from the channel the message was
     *                                                 sent from. Is null when the message happens in the same channel
     *                                                 as the broadcaster. Is not null when in a shared chat session,
     *                                                 and the action happens in the channel of a participant other
     *                                                 than the broadcaster.
     * @param array|null  $sourceBadges                Optional. The list of chat badges
     *                                                 for the chatter in the channel the message was sent from. Is
     *                                                 null when the message happens in the same channel as the
     *                                                 broadcaster. Is not null when in a shared chat session, and the
     *                                                 action happens in the channel of a participant other than the
     *                                                 broadcaster.
     * @param bool|null  $isSourceOnly                 Not yet documented by Twitch
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $chatterUserId,
        public string $chatterUserLogin,
        public string $chatterUserName,
        public string $messageId,
        public ChatMessage $message,
        public string $messageType,
        public array $badges,
        public string $color,
        public ?Cheer $cheer,
        public ?Reply $reply,
        public ?string $channelPointsCustomRewardId,
        public ?string $channelPointsAnimationId,
        public ?string $sourceBroadcasterUserId,
        public ?string $sourceBroadcasterUserLogin,
        public ?string $sourceBroadcasterUserName,
        public ?string $sourceMessageId,
        public ?array $sourceBadges,
        public ?bool $isSourceOnly,
    ) {
    }
}
