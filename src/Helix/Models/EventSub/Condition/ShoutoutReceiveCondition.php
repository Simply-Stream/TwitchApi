<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Condition;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class ShoutoutReceiveCondition implements ConditionInterface
{
    use SerializesModels;

    public function __construct(
        private string $broadcasterUserId,
        private string $moderatorUserId
    ) {
    }

    public function getBroadcasterUserId(): string
    {
        return $this->broadcasterUserId;
    }

    public function getModeratorUserId(): string
    {
        return $this->moderatorUserId;
    }
}
