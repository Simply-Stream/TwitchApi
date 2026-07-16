<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Request;

use SimplyStream\TwitchApi\Helix\Models\Moderation\CheckAutoModStatus;

final readonly class CheckAutoModStatusRequest
{
    /**
     * @param string             $broadcasterId The ID of the broadcaster whose AutoMod settings and list of blocked
     *                                          terms are used to check the message. This ID must match the user ID in
     *                                          the access token.
     * @param CheckAutoModStatus $status        The messages to check.
     */
    public function __construct(
        public string $broadcasterId,
        public CheckAutoModStatus $status,
    ) {
    }
}
