<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\GuestStar\Request;

final readonly class AssignGuestStarSlotRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster running the Guest Star session.
     * @param string $moderatorId   The ID of the broadcaster or a user that has permission to moderate the
     *                             broadcaster’s chat room. This ID must match the user_id in the user access token.
     * @param string $sessionId     The ID of the Guest Star session in which to assign the slot.
     * @param string $guestId       The Twitch User ID corresponding to the guest to assign a slot in the session. This
     *                             user must already have an invite to this session, and have indicated that they are
     *                             ready to join.
     * @param string $slotId        The slot assignment to give to the user. Must be a numeric identifier between “1”
     *                             and “N” where N is the max number of slots for the session.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
        public string $sessionId,
        public string $guestId,
        public string $slotId,
    ) {}
}
