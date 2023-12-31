<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Condition;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class ChannelBanCondition implements ConditionInterface
{
    use SerializesModels;

    /**
     * @param string $broadcasterUserId The broadcaster user ID for the channel you want to get ban notifications for.
     */
    public function __construct(
        private string $broadcasterUserId
    ) {
    }

    public function getBroadcasterUserId(): string
    {
        return $this->broadcasterUserId;
    }
}
