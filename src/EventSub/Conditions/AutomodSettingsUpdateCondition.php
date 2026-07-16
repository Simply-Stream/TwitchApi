<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

class AutomodSettingsUpdateCondition implements ConditionInterface
{
    /**
     * @param string $broadcasterUserId User ID of the broadcaster (channel). Maximum:1.
     * @param string $moderatorUserId   User ID of the moderator.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $moderatorUserId,
    ) {
    }
}
