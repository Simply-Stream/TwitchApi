<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\GuestStar\Request;

final readonly class GetGuestStarSessionRequest
{
    /**
     * @param string $broadcasterId ID for the user hosting the Guest Star session.
     * @param string $moderatorId   The ID of the broadcaster or a user that has permission to moderate the
     *                             broadcaster’s chat room. This ID must match the user ID in the user access token.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
    ) {}
}
