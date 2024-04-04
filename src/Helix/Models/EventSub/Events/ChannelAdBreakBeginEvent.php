<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

use DateTimeInterface;

final readonly class ChannelAdBreakBeginEvent extends Event
{
    /**
     * @param int               $durationSeconds       Length in seconds of the mid-roll ad break requested
     * @param DateTimeInterface $timestamp             The UTC timestamp of when the ad break began, in RFC3339 format.
     *                                                 Note that there is potential delay between this event, when the
     *                                                 streamer requested the ad break, and when the viewers will see
     *                                                 ads.
     * @param bool              $isAutomatic           Indicates if the ad was automatically scheduled via Ads Manager
     * @param string            $requesterUserId       The ID of the user that requested the ad. For automatic ads,
     *                                                 this will be the ID of the broadcaster.
     * @param string            $broadcasterUserId     The broadcaster’s user ID for the channel the ad was run on.
     * @param string            $broadcasterUserLogin  The broadcaster’s user login for the channel the ad was run on.
     * @param string            $broadcasterUserName   The broadcaster’s user display name for the channel the ad was
     *                                                 run on.
     */
    public function __construct(
        private int $durationSeconds,
        private DateTimeInterface $timestamp,
        private bool $isAutomatic,
        private string $requesterUserId,
        private string $broadcasterUserId,
        private string $broadcasterUserLogin,
        private string $broadcasterUserName,
    ) {
    }

    public function getDurationSeconds(): int
    {
        return $this->durationSeconds;
    }

    public function getTimestamp(): DateTimeInterface
    {
        return $this->timestamp;
    }

    public function isAutomatic(): bool
    {
        return $this->isAutomatic;
    }

    public function getRequesterUserId(): string
    {
        return $this->requesterUserId;
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
}
