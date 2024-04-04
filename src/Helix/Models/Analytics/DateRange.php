<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Analytics;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class DateRange
{
    use SerializesModels;

    /**
     * @param DateTimeInterface $startedAt The reporting window’s start date.
     * @param DateTimeInterface $endedAt   The reporting window’s end date.
     */
    public function __construct(
        private DateTimeInterface $startedAt,
        private DateTimeInterface $endedAt
    ) {
    }

    public function getStartedAt(): DateTimeInterface
    {
        return $this->startedAt;
    }

    public function getEndedAt(): DateTimeInterface
    {
        return $this->endedAt;
    }
}
