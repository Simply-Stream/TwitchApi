<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Raids\Request;

final readonly class CancelRaidRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster that initiated the raid. This ID must match the user ID
     *                             associated with the user access token.
     */
    public function __construct(
        public string $broadcasterId,
    ) {}
}
