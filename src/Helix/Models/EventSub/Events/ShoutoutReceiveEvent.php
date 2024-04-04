<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

final readonly class ShoutoutReceiveEvent extends Event
{
    /**
     * @param string             $broadcasterUserId        An ID that identifies the broadcaster that received the
     *                                                     Shoutout.
     * @param string             $broadcasterUserLogin     The broadcaster’s login name.
     * @param string             $broadcasterUserName      The broadcaster’s display name.
     * @param string             $fromBroadcasterUserId    An ID that identifies the broadcaster that sent the
     *                                                     Shoutout.
     * @param string             $fromBroadcasterUserLogin The broadcaster’s login name.
     * @param string             $fromBroadcasterUserName  The broadcaster’s display name.
     * @param int                $viewerCount              The number of users that were watching the
     *                                                     from-broadcaster’s stream at the time of the Shoutout.
     * @param \DateTimeInterface $startedAt                The UTC timestamp (in RFC3339 format) of when the moderator
     *                                                     sent the Shoutout.
     */
    public function __construct(
        private string $broadcasterUserId,
        private string $broadcasterUserLogin,
        private string $broadcasterUserName,
        private string $fromBroadcasterUserId,
        private string $fromBroadcasterUserLogin,
        private string $fromBroadcasterUserName,
        private int $viewerCount,
        private \DateTimeInterface $startedAt
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

    public function getViewerCount(): int
    {
        return $this->viewerCount;
    }

    public function getStartedAt(): \DateTimeInterface
    {
        return $this->startedAt;
    }
}
