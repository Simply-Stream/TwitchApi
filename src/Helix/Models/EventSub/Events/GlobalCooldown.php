<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class GlobalCooldown
{
    use SerializesModels;

    /**
     * @param bool $isEnabled Is the setting enabled.
     * @param int  $seconds   The cooldown in seconds.
     */
    public function __construct(
        private bool $isEnabled,
        private int $seconds
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function getSeconds(): int
    {
        return $this->seconds;
    }
}
