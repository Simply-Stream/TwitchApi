<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class ChannelSuspiciousUserMessageCondition implements ConditionInterface
{
    /**
     * @param string $broadcasterUserId The broadcaster you want to get chat unban request notifications for.
     * @param string $moderatorUserId   The ID of a user that has permission to moderate the broadcaster’s channel
     *                                  and has granted your app permission to subscribe to this subscription type.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $moderatorUserId,
    ) {
    }
}
