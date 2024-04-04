<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Schedule;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Vacation
{
    use SerializesModels;

    /**
     * @param \DateTimeInterface|null $startTime The UTC date and time (in RFC3339 format) of when the broadcaster’s vacation starts.
     * @param \DateTimeInterface|null $endTime   The UTC date and time (in RFC3339 format) of when the broadcaster’s vacation ends.
     */
    public function __construct(
        private ?\DateTimeInterface $startTime = null,
        private ?\DateTimeInterface $endTime = null,
    ) {
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }
}
