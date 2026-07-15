<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class ChannelSharedChatUpdateCondition implements ConditionInterface
{
    /**
     * @param string $broadcasterUserId The User ID of the channel to receive shared chat session update events for.
     */
    public function __construct(
        public string $broadcasterUserId,
    ) {
    }
}
