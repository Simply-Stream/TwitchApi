<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

final readonly class ChannelRaidEvent extends Event
{
    /**
     * @param string $fromBroadcasterUserId    The broadcaster ID that created the raid.
     * @param string $fromBroadcasterUserLogin The broadcaster login that created the raid.
     * @param string $fromBroadcasterUserName  The broadcaster display name that created the raid.
     * @param string $toBroadcasterUserId      The broadcaster ID that received the raid.
     * @param string $toBroadcasterUserLogin   The broadcaster login that received the raid.
     * @param string $toBroadcasterUserName    The broadcaster display name that received the raid.
     * @param int    $viewers                  The number of viewers in the raid.
     */
    public function __construct(
        private string $fromBroadcasterUserId,
        private string $fromBroadcasterUserLogin,
        private string $fromBroadcasterUserName,
        private string $toBroadcasterUserId,
        private string $toBroadcasterUserLogin,
        private string $toBroadcasterUserName,
        private int $viewers
    ) {
    }

    public function getFromBroadcasterUserId(): string
    {
        return $this->fromBroadcasterUserId;
    }

    public function getFromBroadcasterUserLogin(): string
    {
        return $this->fromBroadcasterUserLogin;
    }

    public function getFromBroadcasterUserName(): string
    {
        return $this->fromBroadcasterUserName;
    }

    public function getToBroadcasterUserId(): string
    {
        return $this->toBroadcasterUserId;
    }

    public function getToBroadcasterUserLogin(): string
    {
        return $this->toBroadcasterUserLogin;
    }

    public function getToBroadcasterUserName(): string
    {
        return $this->toBroadcasterUserName;
    }

    public function getViewers(): int
    {
        return $this->viewers;
    }
}
