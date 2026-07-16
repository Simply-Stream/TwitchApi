<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelWarningAcknowledgeCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.warning.acknowledge', version: '1', condition: ChannelWarningAcknowledgeCondition::class)]
final readonly class ChannelWarningAcknowledgeEvent implements EventInterface
{
    /**
     * @param string $broadcasterUserId    The user ID of the broadcaster.
     * @param string $broadcasterUserLogin The login of the broadcaster.
     * @param string $broadcasterUserName  The user name of the broadcaster.
     * @param string $userId               The ID of the user that has acknowledged their warning.
     * @param string $userLogin            The login of the user that has acknowledged their warning.
     * @param string $userName             The user name of the user that has acknowledged their warning.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $userId,
        public string $userLogin,
        public string $userName,
    ) {
    }
}
