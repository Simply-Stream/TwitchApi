<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Chat\Request;

final readonly class GetChannelEmotesRequest
{
    /**
     * @param string $broadcasterId An ID that identifies the broadcaster whose emotes you want to get.
     */
    public function __construct(
        public string $broadcasterId,
    ) {
    }
}
