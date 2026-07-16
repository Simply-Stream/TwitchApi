<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class ChannelGuestStarSessionEndCondition implements ConditionInterface
{
    public function __construct(
        public string $broadcasterUserId,
        public string $moderatorUserId
    ) {
    }
}
