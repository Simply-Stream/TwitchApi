<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

/**
 * Channel Ad Break Begin
 * A midroll commercial break has started running.
 */
final readonly class ChannelAdBreakBeginCondition implements ConditionInterface
{
    public function __construct(
        public string $broadcasterUserId
    ) {
    }
}
