<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\GuestStar\Request;

final readonly class DeleteGuestStarSlotRequest
{
    /**
     * @param string    $broadcasterId         The ID of the broadcaster running the Guest Star session.
     * @param string    $moderatorId           The ID of the broadcaster or a user that has permission to moderate the
     *                                         broadcaster’s chat room. This ID must match the user ID in the user access
     *                                         token.
     * @param string    $sessionId             The ID of the Guest Star session in which to remove the slot assignment.
     * @param string    $guestId               The Twitch User ID corresponding to the guest to remove from the session.
     * @param string    $slotId                The slot ID representing the slot assignment to remove from the session.
     * @param bool|null $shouldReinviteGuest   Flag signaling that the guest should be reinvited to the session, sending
     *                                         them back to the invite queue.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
        public string $sessionId,
        public string $guestId,
        public string $slotId,
        public ?bool $shouldReinviteGuest = null,
    ) {
    }
}
