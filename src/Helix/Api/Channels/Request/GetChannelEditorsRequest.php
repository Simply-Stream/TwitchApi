<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Channels\Request;

final readonly class GetChannelEditorsRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster that owns the channel. This ID must match the user ID in
     *                             the access token.
     */
    public function __construct(
        public string $broadcasterId,
    ) {
    }
}
