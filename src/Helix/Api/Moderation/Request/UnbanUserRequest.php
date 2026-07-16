<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Request;

final readonly class UnbanUserRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster whose chat room the user is banned from chatting in.
     * @param string $moderatorId   The ID of the broadcaster or a user that has permission to moderate the
     *                             broadcaster’s chat room. This ID must match the user ID in the user access token.
     * @param string $userId        The ID of the user to remove the ban or timeout from.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
        public string $userId,
    ) {
    }
}
