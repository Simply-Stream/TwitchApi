<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Request;

final readonly class RemoveChannelVipRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster who owns the channel where the user has VIP status.
     * @param string $userId        The ID of the user to remove VIP status from.
     */
    public function __construct(
        public string $broadcasterId,
        public string $userId,
    ) {
    }
}
