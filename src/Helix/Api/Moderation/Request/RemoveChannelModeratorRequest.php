<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Request;

final readonly class RemoveChannelModeratorRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster that owns the chat room. This ID must match the user ID
     *                             in the access token.
     * @param string $userId        The ID of the user to remove as a moderator from the broadcaster’s chat room.
     */
    public function __construct(
        public string $broadcasterId,
        public string $userId,
    ) {
    }
}
