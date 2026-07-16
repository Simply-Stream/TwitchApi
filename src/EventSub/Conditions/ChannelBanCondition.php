<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class ChannelBanCondition implements ConditionInterface
{
    /**
     * @param string $broadcasterUserId The broadcaster user ID for the channel you want to get ban notifications for.
     */
    public function __construct(
        public string $broadcasterUserId
    ) {
    }
}
