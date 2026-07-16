<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class ChannelSuspiciousUserUpdateCondition implements ConditionInterface
{
    /**
     * @param string $broadcasterUserId User ID of the channel to receive suspicious user update events for.
     * @param string $moderatorUserId   The ID of a user that has permission to moderate the broadcaster’s channel
     *                                  and has granted your app permission to subscribe to this subscription type.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $moderatorUserId,
    ) {
    }
}
