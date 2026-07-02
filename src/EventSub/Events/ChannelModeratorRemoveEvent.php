<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelModeratorRemoveCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.moderator.remove', version: '1', condition: ChannelModeratorRemoveCondition::class)]
final readonly class ChannelModeratorRemoveEvent implements EventInterface
{
    /**
     * @param string $broadcasterUserId    The requested broadcaster ID.
     * @param string $broadcasterUserLogin The requested broadcaster login.
     * @param string $broadcasterUserName  The requested broadcaster display name.
     * @param string $userId               The user ID of the removed moderator.
     * @param string $userLogin            The user login of the removed moderator.
     * @param string $userName             The display name of the removed moderator.
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
