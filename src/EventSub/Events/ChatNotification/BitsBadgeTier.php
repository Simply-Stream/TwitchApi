<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChatNotification;

final readonly class BitsBadgeTier
{
    /**
     * @param int $tier The tier of the Bits badge the user just earned. For example, 100, 1000, or 10000.
     */
    public function __construct(
        public int $tier
    ) {
    }
}
