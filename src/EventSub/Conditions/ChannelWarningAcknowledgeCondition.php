<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class ChannelWarningAcknowledgeCondition implements ConditionInterface
{
    /**
     * @param string $broadcasterUserId The User ID of the broadcaster.
     * @param string $moderatorUserId   The User ID of the moderator.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $moderatorUserId,
    ) {
    }
}
