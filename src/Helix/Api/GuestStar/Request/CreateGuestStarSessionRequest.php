<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\GuestStar\Request;

final readonly class CreateGuestStarSessionRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster you want to create a Guest Star session for. Provided
     *                             broadcaster_id must match the user_id in the auth token.
     */
    public function __construct(
        public string $broadcasterId,
    ) {
    }
}
