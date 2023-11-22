<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Ads;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class SnoozeNextAd
{
    use SerializesModels;

    /**
     * @param int               $snoozeCount      The number of snoozes available for the broadcaster.
     * @param DateTimeImmutable $snoozeRefreshAt  The UTC timestamp when the broadcaster will gain an additional
     *                                            snooze, in RFC3339 format.
     * @param DateTimeImmutable $nextAdAt         The UTC timestamp of the broadcaster’s next scheduled ad, in RFC3339
     *                                            format.
     */
    public function __construct(
        private int $snoozeCount,
        private DateTimeImmutable $snoozeRefreshAt,
        private DateTimeImmutable $nextAdAt
    ) {
    }

    public function getSnoozeCount(): int
    {
        return $this->snoozeCount;
    }

    public function getSnoozeRefreshAt(): DateTimeImmutable
    {
        return $this->snoozeRefreshAt;
    }

    public function getNextAdAt(): DateTimeImmutable
    {
        return $this->nextAdAt;
    }
}
