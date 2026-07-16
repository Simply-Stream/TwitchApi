<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChannelPointCustomReward;

final readonly class MaxPerStream
{
    /**
     * @param bool $isEnabled Is the setting enabled.
     * @param int  $value     The max per stream limit.
     */
    public function __construct(
        public bool $isEnabled,
        public int $value
    ) {
    }
}
