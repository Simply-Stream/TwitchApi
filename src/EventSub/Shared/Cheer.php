<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Shared;

final readonly class Cheer
{
    /**
     * @param int $bits The amount of Bits the user cheered.
     */
    public function __construct(
        public int $bits,
    ) {
    }
}
