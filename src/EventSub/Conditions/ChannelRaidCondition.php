<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class ChannelRaidCondition implements ConditionInterface
{
    public function __construct(
        public ?string $fromBroadcasterUserId = null,
        public ?string $toBroadcasterUserId = null
    ) {
    }
}
