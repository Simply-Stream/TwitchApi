<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Clips\Request;

final readonly class CreateClipRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster whose stream you want to create a clip from.
     * @param bool   $hasDelay      A Boolean value that determines whether the API captures the clip at the moment the
     *                             viewer requests it or after a delay. If false (default), Twitch captures the clip at
     *                             the moment the viewer requests it (this is the same clip experience as the Twitch
     *                             UX). If true, Twitch adds a delay before capturing the clip (this basically shifts
     *                             the capture window to the right slightly).
     */
    public function __construct(
        public string $broadcasterId,
        public bool $hasDelay = false,
    ) {
    }
}
