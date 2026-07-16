<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Schedule;

final readonly class ChannelStreamSchedule
{
    /**
     * @param list<ScheduleSegment> $segments         A list of broadcast segments in the schedule.
     * @param string                $broadcasterId    The ID of the broadcaster that owns the broadcast schedule.
     * @param string                $broadcasterName  The broadcaster’s display name.
     * @param string                $broadcasterLogin The broadcaster’s login name.
     * @param Vacation|null         $vacation         The dates when the broadcaster is on vacation and not streaming.
     *                                                Is set to null if vacation mode is not enabled.
     */
    public function __construct(
        public array $segments,
        public string $broadcasterId,
        public string $broadcasterName,
        public string $broadcasterLogin,
        public ?Vacation $vacation = null,
    ) {
    }
}
