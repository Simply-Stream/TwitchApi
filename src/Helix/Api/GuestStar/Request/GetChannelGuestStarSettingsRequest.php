<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\GuestStar\Request;

final readonly class GetChannelGuestStarSettingsRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster you want to get guest star settings for.
     * @param string $moderatorId   The ID of the broadcaster or a user that has permission to moderate the
     *                             broadcaster’s chat room. This ID must match the user ID in the user access token.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
    ) {
    }
}
