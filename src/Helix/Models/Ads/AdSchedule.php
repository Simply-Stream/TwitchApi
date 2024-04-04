<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Ads;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class AdSchedule
{
    use SerializesModels;

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
        private int $snoozeCount,
        private DateTimeInterface $snoozeRefreshAt,
        private DateTimeInterface $nextAdAt,
        private int $duration,
        private DateTimeInterface $lastAdAt,
        private int $prerollFreeTime
    ) {
    }

    public function getSnoozeCount(): int
    {
        return $this->snoozeCount;
    }

    public function getSnoozeRefreshAt(): DateTimeInterface
    {
        return $this->snoozeRefreshAt;
    }

    public function getNextAdAt(): DateTimeInterface
    {
        return $this->nextAdAt;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function getLastAdAt(): DateTimeInterface
    {
        return $this->lastAdAt;
    }

    public function getPrerollFreeTime(): int
    {
        return $this->prerollFreeTime;
    }
}
