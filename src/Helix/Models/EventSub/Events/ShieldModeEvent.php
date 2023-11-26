<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

final readonly class ShieldModeEvent extends Event
{
    public function __construct(
        private string $broadcasterUserId,
        private string $broadcasterUserLogin,
        private string $broadcasterUserName,
        private string $moderatorUserId,
        private string $moderatorUserLogin,
        private string $moderatorUserName,
        private ?\DateTimeImmutable $startedAt = null,
        private ?\DateTimeImmutable $endedAt = null
    ) {
    }

    public function getBroadcasterUserId(): string
    {
        return $this->broadcasterUserId;
    }

    public function getBroadcasterUserLogin(): string
    {
        return $this->broadcasterUserLogin;
    }

    public function getBroadcasterUserName(): string
    {
        return $this->broadcasterUserName;
    }

    public function getModeratorUserId(): string
    {
        return $this->moderatorUserId;
    }

    public function getModeratorUserLogin(): string
    {
        return $this->moderatorUserLogin;
    }

    public function getModeratorUserName(): string
    {
        return $this->moderatorUserName;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }
}
