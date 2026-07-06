<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Ads\Request;

final readonly class SnoozeNextAdRequest
{
    /**
     * @param string $broadcasterId Provided broadcaster_id must match the user_id in the auth token.
     */
    public function __construct(
        public string $broadcasterId,
    ) {
    }
}
