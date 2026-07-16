<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Ads;

use DateTimeInterface;

final readonly class AdSchedule
{
    /**
     * @param int               $snoozeCount             The number of snoozes available for the broadcaster.
     * @param DateTimeInterface $snoozeRefreshAt         The UTC timestamp when the broadcaster will gain an additional
     *                                                   snooze, in RFC3339 format.
     * @param DateTimeInterface $nextAdAt                The UTC timestamp of the broadcaster’s next scheduled ad, in
     *                                                   RFC3339 format. Empty if the channel has no ad scheduled or is
     *                                                   not live.
     * @param int               $duration                The length in seconds of the scheduled upcoming ad break.
     * @param DateTimeInterface $lastAdAt                The UTC timestamp of the broadcaster’s last ad-break, in
     *                                                   RFC3339 format. Empty if the channel has not run an ad or is
     *                                                   not live.
     * @param int               $prerollFreeTime         The amount of pre-roll free time remaining for the channel in
     *                                                   seconds. Returns 0 if they are currently not pre-roll free.
     */
    public function __construct(
        public int $snoozeCount,
        public DateTimeInterface $snoozeRefreshAt,
        public DateTimeInterface $nextAdAt,
        public int $duration,
        public DateTimeInterface $lastAdAt,
        public int $prerollFreeTime
    ) {
    }
}
