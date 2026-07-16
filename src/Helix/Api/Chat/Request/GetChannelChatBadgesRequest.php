<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Chat\Request;

final readonly class GetChannelChatBadgesRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster whose chat badges you want to get.
     */
    public function __construct(
        public string $broadcasterId,
    ) {
    }
}
