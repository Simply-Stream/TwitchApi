<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class ChannelBitsUseCondition implements ConditionInterface
{
    /**
     * @param string $broadcasterUserId The user ID of the channel broadcaster. Maximum: 1.
     */
    public function __construct(
        public string $broadcasterUserId
    ) {
    }
}
