<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class ChannelSharedChatEndCondition implements ConditionInterface
{
    /**
     * @param string $broadcasterUserId The User ID of the channel to receive shared chat session end events for.
     */
    public function __construct(
        public string $broadcasterUserId,
    ) {
    }
}
