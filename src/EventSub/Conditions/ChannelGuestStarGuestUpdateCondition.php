<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class ChannelGuestStarGuestUpdateCondition implements ConditionInterface
{
    /**
     * @param string $broadcasterUserId The broadcaster user ID of the channel hosting the Guest Star Session.
     * @param string $moderatorUserId   The user ID of the moderator or broadcaster of the specified channel.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $moderatorUserId,
    ) {
    }
}
