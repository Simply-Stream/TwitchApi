<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Schedule\Request;

final readonly class DeleteStreamScheduleSegmentRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster that owns the streaming schedule. This ID must match the
     *                             user ID in the user access token.
     * @param string $id            The ID of the broadcast segment to remove.
     */
    public function __construct(
        public string $broadcasterId,
        public string $id,
    ) {
    }
}
