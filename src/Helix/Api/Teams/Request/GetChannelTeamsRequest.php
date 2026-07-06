<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Teams\Request;

final readonly class GetChannelTeamsRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster whose teams you want to get.
     */
    public function __construct(
        public string $broadcasterId,
    ) {}
}
