<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Raids\Request;

final readonly class StartRaidRequest
{
    /**
     * @param string $fromBroadcasterId The ID of the broadcaster that’s sending the raiding party. This ID must match
     *                                 the user ID associated with the user access token.
     * @param string $toBroadcasterId   The ID of the broadcaster to raid.
     */
    public function __construct(
        public string $fromBroadcasterId,
        public string $toBroadcasterId,
    ) {}
}
