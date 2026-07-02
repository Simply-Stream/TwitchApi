<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelChatClearUserMessagesCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.chat.clear_user_messages', version: '1', condition: ChannelChatClearUserMessagesCondition::class)]
final readonly class ChannelChatClearUserMessagesEvent implements EventInterface
{
    /**
     * @param string $broadcasterUserId    The broadcaster user ID.
     * @param string $broadcasterUserLogin The broadcaster login.
     * @param string $broadcasterUserName  The broadcaster display name.
     * @param string $targetUserId         The ID of the user that was banned or put in a timeout. All of their
     *                                     messages are deleted.
     * @param string $targetUserLogin      The user login of the user that was banned or put in a timeout.
     * @param string $targetUserName       The user name of the user that was banned or put in a timeout.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $targetUserId,
        public string $targetUserLogin,
        public string $targetUserName,
    ) {
    }
}
