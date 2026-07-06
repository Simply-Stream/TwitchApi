<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Ads;

use DateTimeInterface;

final readonly class SnoozeNextAd
{
    /**
     * @param int               $snoozeCount      The number of snoozes available for the broadcaster.
     * @param DateTimeInterface $snoozeRefreshAt  The UTC timestamp when the broadcaster will gain an additional
     *                                            snooze, in RFC3339 format.
     * @param DateTimeInterface $nextAdAt         The UTC timestamp of the broadcaster’s next scheduled ad, in RFC3339
     *                                            format.
     */
    public function __construct(
        public int $snoozeCount,
        public DateTimeInterface $snoozeRefreshAt,
        public DateTimeInterface $nextAdAt
    ) {
    }
}
