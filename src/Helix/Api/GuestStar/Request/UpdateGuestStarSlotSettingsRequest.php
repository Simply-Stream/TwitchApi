<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\GuestStar\Request;

use Webmozart\Assert\Assert;

final readonly class UpdateGuestStarSlotSettingsRequest
{
    /**
     * @param string    $broadcasterId  The ID of the broadcaster running the Guest Star session.
     * @param string    $moderatorId    The ID of the broadcaster or a user that has permission to moderate the
     *                                  broadcaster’s chat room. This ID must match the user ID in the user access token.
     * @param string    $sessionId      The ID of the Guest Star session in which to update a slot’s settings.
     * @param string    $slotId         The slot assignment that has previously been assigned to a user.
     * @param bool|null $isAudioEnabled Flag indicating whether the slot is allowed to share their audio with the rest
     *                                  of the session. If false, the slot will be muted in any views containing the
     *                                  slot.
     * @param bool|null $isVideoEnabled Flag indicating whether the slot is allowed to share their video with the rest
     *                                  of the session. If false, the slot will have no video shared in any views
     *                                  containing the slot.
     * @param bool|null $isLive         Flag indicating whether the user assigned to this slot is visible/can be heard
     *                                  from any public subscriptions. Generally, this determines whether or not the
     *                                  slot is enabled in any broadcasting software integrations.
     * @param int|null  $volume         Value from 0-100 that controls the audio volume for shared views containing the
     *                                  slot.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
        public string $sessionId,
        public string $slotId,
        public ?bool $isAudioEnabled = null,
        public ?bool $isVideoEnabled = null,
        public ?bool $isLive = null,
        public ?int $volume = null,
    ) {
        if ($volume !== null) {
            Assert::range($volume, 0, 100);
        }
    }
}
