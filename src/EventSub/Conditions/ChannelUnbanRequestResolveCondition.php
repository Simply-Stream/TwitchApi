<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class ChannelUnbanRequestResolveCondition implements ConditionInterface
{
    /**
     * @param string $broadcasterUserId The ID of the broadcaster you want to get unban request resolution
     *                                  notifications for. Maximum: 1.
     * @param string $moderatorUserId   The ID of the user that has permission to moderate the broadcaster’s channel
     *                                  and has granted your app permission to subscribe to this subscription type.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $moderatorUserId,
    ) {
    }
}
