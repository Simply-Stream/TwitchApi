<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Condition;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

/**
 * (BETA) Channel Ad Break Begin
 * A midroll commercial break has started running.
 */
final readonly class ChannelAdBreakBeginCondition implements ConditionInterface
{
    use SerializesModels;

    public function __construct(
        private string $broadcasterUserId
    ) {
    }

    public function getBroadcasterUserId(): string
    {
        return $this->broadcasterUserId;
    }
}
