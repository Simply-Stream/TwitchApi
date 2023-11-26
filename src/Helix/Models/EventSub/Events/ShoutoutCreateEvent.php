<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

final readonly class ShoutoutCreateEvent extends Event
{
    /**
     * @param string             $broadcasterUserId      An ID that identifies the broadcaster that sent the Shoutout.
     * @param string             $broadcasterUserLogin   The broadcaster’s login name.
     * @param string             $broadcasterUserName    The broadcaster’s display name.
     * @param string             $toBroadcasterUserId    An ID that identifies the broadcaster that received the
     *                                                   Shoutout.
     * @param string             $toBroadcasterUserLogin The broadcaster’s login name.
     * @param string             $toBroadcasterUserName  The broadcaster’s display name.
     * @param string             $moderatorUserId        An ID that identifies the moderator that sent the Shoutout. If
     *                                                   the broadcaster sent the Shoutout, this ID is the same as the
     *                                                   ID in broadcaster_user_id.
     * @param string             $moderatorUserLogin     The moderator’s login name.
     * @param string             $moderatorUserName      The moderator’s display name.
     * @param int                $viewerCount            The number of users that were watching the broadcaster’s
     *                                                   stream at the time of the Shoutout.
     * @param \DateTimeImmutable $startedAt              The UTC timestamp (in RFC3339 format) of when the moderator
     *                                                   sent the Shoutout.
     * @param \DateTimeImmutable $cooldownEndsAt         The UTC timestamp (in RFC3339 format) of when the broadcaster
     *                                                   may send a Shoutout to a different broadcaster.
     * @param \DateTimeImmutable $targetCooldownEndsAt   The UTC timestamp (in RFC3339 format) of when the broadcaster
     *                                                   may send another Shoutout to the broadcaster in
     *                                                   to_broadcaster_user_id.
     */
    public function __construct(
        private string $broadcasterUserId,
        private string $broadcasterUserLogin,
        private string $broadcasterUserName,
        private string $toBroadcasterUserId,
        private string $toBroadcasterUserLogin,
        private string $toBroadcasterUserName,
        private string $moderatorUserId,
        private string $moderatorUserLogin,
        private string $moderatorUserName,
        private int $viewerCount,
        private \DateTimeImmutable $startedAt,
        private \DateTimeImmutable $cooldownEndsAt,
        private \DateTimeImmutable $targetCooldownEndsAt,
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

    public function getViewerCount(): int
    {
        return $this->viewerCount;
    }

    public function getStartedAt(): \DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getCooldownEndsAt(): \DateTimeImmutable
    {
        return $this->cooldownEndsAt;
    }

    public function getTargetCooldownEndsAt(): \DateTimeImmutable
    {
        return $this->targetCooldownEndsAt;
    }
}
