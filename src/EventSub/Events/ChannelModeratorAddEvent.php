<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelModeratorAddCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.moderator.add', version: '1', condition: ChannelModeratorAddCondition::class)]
final readonly class ChannelModeratorAddEvent implements EventInterface
{
    /**
     * @param string $broadcasterUserId    The requested broadcaster ID.
     * @param string $broadcasterUserLogin The requested broadcaster login.
     * @param string $broadcasterUserName  The requested broadcaster display name.
     * @param string $userId               The user ID of the new moderator.
     * @param string $userLogin            The user login of the new moderator.
     * @param string $userName             The display name of the new moderator.
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
