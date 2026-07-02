<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

final readonly class GlobalCooldown
{
    /**
     * @param bool $isEnabled Is the setting enabled.
     * @param int  $seconds   The cooldown in seconds.
     */
    public function __construct(
        public bool $isEnabled,
        public int $seconds
    ) {
    }
}
