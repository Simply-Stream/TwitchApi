<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Schedule;

use DateTimeInterface;

final readonly class Vacation
{
    /**
     * @param DateTimeInterface|null $startTime The UTC date and time (in RFC3339 format) of when the broadcaster’s
     *                                          vacation starts.
     * @param DateTimeInterface|null $endTime   The UTC date and time (in RFC3339 format) of when the broadcaster’s
     *                                          vacation ends.
     */
    public function __construct(
        public ?DateTimeInterface $startTime = null,
        public ?DateTimeInterface $endTime = null,
    ) {
    }
}
