<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\GuestStar;

use DateTimeInterface;

final readonly class GuestStarInvite
{
    /**
     * @param string            $userId           Twitch User ID corresponding to the invited guest
     * @param DateTimeInterface $invitedAt        Timestamp when this user was invited to the session.
     * @param string            $status           Status representing the invited user’s join state. Can be one of the
     *                                            following:
     *                                            - INVITED
     *                                            - ACCEPTED
     *                                            - READY
     * @param bool              $isVideoEnabled   Flag signaling that the invited user has chosen to disable their
     *                                            local video device.
     * @param bool              $isAudioEnabled   Flag signaling that the invited user has chosen to disable their
     *                                            local audio device.
     * @param bool              $isVideoAvailable Flag signaling that the invited user has a video device available for
     *                                            sharing.
     * @param bool              $isAudioAvailable Flag signaling that the invited user has an audio device available for
     *                                            sharing.
     */
    public function __construct(
        public string $userId,
        public DateTimeInterface $invitedAt,
        public string $status,
        public bool $isVideoEnabled,
        public bool $isAudioEnabled,
        public bool $isVideoAvailable,
        public bool $isAudioAvailable,
    ) {
    }
}
