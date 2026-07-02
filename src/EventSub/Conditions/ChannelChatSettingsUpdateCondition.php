<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class ChannelChatSettingsUpdateCondition implements ConditionInterface
{
    /**
     * @param string $broadcasterUserId User ID of the channel to receive chat settings update events for.
     * @param string $userId            The user ID to read chat as.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $userId,
    ) {
    }
}
