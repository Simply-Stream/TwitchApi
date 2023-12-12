<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Schedule;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Vacation
{
    use SerializesModels;

    /**
     * @param \DateTimeImmutable|null $startTime The UTC date and time (in RFC3339 format) of when the broadcaster’s vacation starts.
     * @param \DateTimeImmutable|null $endTime   The UTC date and time (in RFC3339 format) of when the broadcaster’s vacation ends.
     */
    public function __construct(
        private ?\DateTimeImmutable $startTime = null,
        private ?\DateTimeImmutable $endTime = null,
    ) {
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->endTime;
    }
}
