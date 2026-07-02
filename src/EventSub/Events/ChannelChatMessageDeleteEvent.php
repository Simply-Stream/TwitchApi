<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelChatMessageDeleteCondition;

#[EventSubSubscription(type: 'channel.chat.message_delete', version: '1', condition: ChannelChatMessageDeleteCondition::class)]
final readonly class ChannelChatMessageDeleteEvent
{
    /**
     * @param string $broadcasterUserId    The broadcaster user ID.
     * @param string $broadcasterUserLogin The broadcaster login.
     * @param string $broadcasterUserName  The broadcaster display name.
     * @param string $targetUserId         The ID of the user whose message was deleted.
     * @param string $targetUserLogin      The user name of the user whose message was deleted.
     * @param string $targetUserName       The user login of the user whose message was deleted.
     * @param string $messageId            A UUID that identifies the message that was removed.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $targetUserId,
        public string $targetUserLogin,
        public string $targetUserName,
        public string $messageId
    ) {
    }
}
