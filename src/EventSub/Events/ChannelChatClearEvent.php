<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelChatClearCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.chat.clear', version: '1', condition: ChannelChatClearCondition::class)]
final readonly class ChannelChatClearEvent implements EventInterface
{
    /**
     * @param string $broadcasterUserId    The broadcaster user ID.
     * @param string $broadcasterUserLogin The broadcaster login.
     * @param string $broadcasterUserName  The broadcaster display name.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName
    ) {
    }
}
