<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class ChannelShieldModeBeginCondition implements ConditionInterface
{
    /**
     * @param string $broadcasterUserId The ID of the broadcaster whose Shield Mode status you want to receive
     *                                  notifications about.
     * @param string $moderatorUserId   The ID of the broadcaster or one of the broadcaster’s moderators.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $moderatorUserId,
    ) {
    }
}
