<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

use DateTimeInterface;

final readonly class ChannelGuestStarSessionEndEvent extends Event
{
    /**
     * @param string            $broadcasterUserId    The broadcaster user ID
     * @param string            $broadcasterUserName  The broadcaster display name
     * @param string            $broadcasterUserLogin The broadcaster login
     * @param string            $sessionId            ID representing the unique session that was started.
     * @param DateTimeInterface $startedAt            RFC3339 timestamp indicating the time the session began.
     * @param DateTimeInterface $endedAt              RFC3339 timestamp indicating the time the session ended.
     */
    public function __construct(
        private string $broadcasterUserId,
        private string $broadcasterUserName,
        private string $broadcasterUserLogin,
        private string $sessionId,
        private DateTimeInterface $startedAt,
        private DateTimeInterface $endedAt
    ) {
    }

    public function getBroadcasterUserId(): string
    {
        return $this->broadcasterUserId;
    }

    public function getBroadcasterUserName(): string
    {
        return $this->broadcasterUserName;
    }

    public function getBroadcasterUserLogin(): string
    {
        return $this->broadcasterUserLogin;
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
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
