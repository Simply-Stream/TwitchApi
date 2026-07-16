<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Goals\Request;

final readonly class GetCreatorGoalsRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster that created the goals. This ID must match the user ID in
     *                             the user access token.
     */
    public function __construct(
        public string $broadcasterId,
    ) {
    }
}
