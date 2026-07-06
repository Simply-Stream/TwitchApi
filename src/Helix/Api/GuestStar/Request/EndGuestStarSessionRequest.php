<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\GuestStar\Request;

final readonly class EndGuestStarSessionRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster you want to end a Guest Star session for. Provided
     *                             broadcaster_id must match the user_id in the auth token.
     * @param string $sessionId     ID for the session to end on behalf of the broadcaster.
     */
    public function __construct(
        public string $broadcasterId,
        public string $sessionId,
    ) {}
}
