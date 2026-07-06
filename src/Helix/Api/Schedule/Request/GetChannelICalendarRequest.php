<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Schedule\Request;

final readonly class GetChannelICalendarRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster that owns the streaming schedule you want to get.
     */
    public function __construct(
        public string $broadcasterId,
    ) {}
}
