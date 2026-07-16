<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class ChannelVipAddCondition implements ConditionInterface
{
    /**
     * @param string $broadcasterUserId The User ID of the broadcaster (channel). Maximum: 1.
     */
    public function __construct(
        public string $broadcasterUserId,
    ) {
    }
}
