<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Request;

final readonly class GetShieldModeStatusRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster whose Shield Mode activation status you want to get.
     * @param string $moderatorId   The ID of the broadcaster or a user that is one of the broadcaster’s moderators.
     *                             This ID must match the user ID in the access token.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
    ) {}
}
