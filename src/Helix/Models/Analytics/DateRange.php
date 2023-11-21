<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Analytics;

use DateTimeImmutable;

final readonly class DateRange
{
    /**
     * @param DateTimeImmutable $startedAt The reporting window’s start date.
     * @param DateTimeImmutable $endedAt   The reporting window’s end date.
     */
    public function __construct(
        private DateTimeImmutable $startedAt,
        private DateTimeImmutable $endedAt
    ) {
    }

    public function getStartedAt(): DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getEndedAt(): DateTimeImmutable
    {
        return $this->endedAt;
    }
}
