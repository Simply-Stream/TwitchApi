<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelChatMessageCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Shared\Badge;
use SimplyStream\TwitchApi\EventSub\Shared\Cheer;
use SimplyStream\TwitchApi\EventSub\Shared\Message as ChatMessage;
use SimplyStream\TwitchApi\EventSub\Shared\Reply;

#[EventSubSubscription(type: 'channel.chat.message', version: '1', condition: ChannelChatMessageCondition::class)]
final readonly class ChannelChatMessageEvent implements EventInterface
{
    /**
     * @param string       $broadcasterUserId           The broadcaster user ID.
     * @param string       $broadcasterUserLogin        The broadcaster login.
     * @param string       $broadcasterUserName         The broadcaster display name.
     * @param string       $chatterUserId               The user ID of the user that sent the message.
     * @param string       $chatterUserLogin            The user login of the user that sent the message.
     * @param string       $chatterUserName             The user name of the user that sent the message.
     * @param string       $messageId                   A UUID that identifies the message.
     * @param ChatMessage  $message                     The structured chat message.
     * @param string       $messageType                 The type of message. Possible values: text,
     *                                                  channel_points_highlighted, channel_points_sub_only,
     *                                                  user_intro, power_ups_message_effect,
     *                                                  power_ups_gigantified_emote.
     * @param Badge[]      $badges                      List of chat badges.
     * @param string       $color                       The color of the user’s name in the chat room. Hexadecimal RGB
     *                                                  code (#RGB). May be empty if never set.
     * @param Cheer|null   $cheer                       Optional. Metadata if this message is a cheer.
     * @param Reply|null   $reply                       Optional. Metadata if this message is a reply.
     * @param string|null  $channelPointsCustomRewardId Optional. The ID of a channel points custom reward that was
     *                                                  redeemed.
     * @param string|null  $channelPointsAnimationId    Optional. Not documented by Twitch, but present in request.
     * @param string|null  $sourceBroadcasterUserId     Optional. Broadcaster user ID of the source channel in a shared
     *                                                  chat session. Null in the broadcaster’s own channel.
     * @param string|null  $sourceBroadcasterUserLogin  Optional. Login of the source broadcaster in a shared chat
     *                                                  session. Null in the broadcaster’s own channel.
     * @param string|null  $sourceBroadcasterUserName   Optional. User name of the source broadcaster in a shared chat
     *                                                  session. Null in the broadcaster’s own channel.
     * @param string|null  $sourceMessageId             Optional. UUID of the source message in a shared chat session.
     *                                                  Null in the broadcaster’s own channel.
     * @param Badge[]|null $sourceBadges                Optional. Chat badges for the chatter in the source channel of
     *                                                  a shared chat session. Null in the broadcaster’s own channel.
     * @param bool|null    $isSourceOnly                Optional. Determines if a message delivered during a shared
     *                                                  chat session is only sent to the source channel.
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
        public ?Cheer $cheer = null,
        public ?Reply $reply = null,
        public ?string $channelPointsCustomRewardId = null,
        public ?string $channelPointsAnimationId = null,
        public ?string $sourceBroadcasterUserId = null,
        public ?string $sourceBroadcasterUserLogin = null,
        public ?string $sourceBroadcasterUserName = null,
        public ?string $sourceMessageId = null,
        public ?array $sourceBadges = null,
        public ?bool $isSourceOnly = null,
    ) {
    }
}
