<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChannelPointCustomReward;

final readonly class MaxPerUserPerStream
{
    /**
     * @param bool $isEnabled Is the setting enabled.
     * @param int  $value     The max per user per stream limit.
     */
    public function __construct(
        public bool $isEnabled,
        public int $value
    ) {
    }
}
