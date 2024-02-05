<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

final readonly class Cheer
{
    /**
     * @param int $bits The amount of Bits the user cheered.
     */
    public function __construct(
        private int $bits,
    ) {
    }

    public function getBits(): int
    {
        return $this->bits;
    }
}
