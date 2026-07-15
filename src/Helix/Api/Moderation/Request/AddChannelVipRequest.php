<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Request;

final readonly class AddChannelVipRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster that’s adding the user as a VIP. This ID must match the
     *                             user ID in the access token.
     * @param string $userId        The ID of the user to give VIP status to.
     */
    public function __construct(
        public string $broadcasterId,
        public string $userId,
    ) {
    }
}
