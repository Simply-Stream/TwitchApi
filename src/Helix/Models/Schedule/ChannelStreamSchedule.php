<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Schedule;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class ChannelStreamSchedule
{
    use SerializesModels;

    /**
     * @param ScheduleSegment[] $segments         A list that contains the single broadcast segment that you added.
     * @param string            $broadcasterId    The ID of the broadcaster that owns the broadcast schedule.
     * @param string            $broadcasterName  The broadcaster’s display name.
     * @param string            $broadcasterLogin The broadcaster’s login name.
     * @param Vacation|null     $vacation         The dates when the broadcaster is on vacation and not streaming. Is set to null if
     *                                            vacation mode is not enabled.
     */
    public function __construct(
        private array $segments,
        private string $broadcasterId,
        private string $broadcasterName,
        private string $broadcasterLogin,
        private ?Vacation $vacation = null,
    ) {
    }

    public function getSegments(): array
    {
        return $this->segments;
    }

    public function getBroadcasterId(): string
    {
        return $this->broadcasterId;
    }

    public function getBroadcasterName(): string
    {
        return $this->broadcasterName;
    }

    public function getBroadcasterLogin(): string
    {
        return $this->broadcasterLogin;
    }

    public function getVacation(): ?Vacation
    {
        return $this->vacation;
    }
}
