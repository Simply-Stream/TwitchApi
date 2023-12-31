<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class MaxPerUserPerStream
{
    use SerializesModels;

    /**
     * @param bool $isEnabled Is the setting enabled.
     * @param int  $value     The max per user per stream limit.
     */
    public function __construct(
        private bool $isEnabled,
        private int $value
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
