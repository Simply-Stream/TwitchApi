<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Streams\Request;

final readonly class GetStreamKeyRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster that owns the channel. The ID must match the user ID in
     *                             the access token.
     */
    public function __construct(
        public string $broadcasterId,
    ) {}
}
