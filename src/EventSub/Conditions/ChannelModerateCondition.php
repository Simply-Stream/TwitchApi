<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class ChannelModerateCondition implements ConditionInterface
{
    /**
     * @param string $broadcasterUserId The user ID of the broadcaster.
     * @param string $moderatorUserId   The user ID of the moderator.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $moderatorUserId,
    ) {
    }
}
