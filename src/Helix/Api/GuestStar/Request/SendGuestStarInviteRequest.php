<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\GuestStar\Request;

final readonly class SendGuestStarInviteRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster running the Guest Star session.
     * @param string $moderatorId   The ID of the broadcaster or a user that has permission to moderate the
     *                             broadcaster’s chat room. This ID must match the user_id in the user access token.
     * @param string $sessionId     The session ID for the invite to be sent on behalf of the broadcaster.
     * @param string $guestId       Twitch User ID for the guest to invite to the Guest Star session.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
        public string $sessionId,
        public string $guestId,
    ) {}
}
