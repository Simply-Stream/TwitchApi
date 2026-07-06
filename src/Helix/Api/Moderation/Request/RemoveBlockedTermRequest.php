<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Request;

final readonly class RemoveBlockedTermRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster that owns the list of blocked terms.
     * @param string $moderatorId   The ID of the broadcaster or a user that has permission to moderate the
     *                             broadcaster’s chat room. This ID must match the user ID in the user access token.
     * @param string $id            The ID of the blocked term to remove from the broadcaster’s list of blocked terms.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
        public string $id,
    ) {}
}
