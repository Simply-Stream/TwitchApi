<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request;

final readonly class DeleteCustomRewardRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster that created the custom reward. This ID must match the
     *                             user ID found in the OAuth token.
     * @param string $id            The ID of the custom reward to delete.
     */
    public function __construct(
        public string $broadcasterId,
        public string $id,
    ) {
    }
}
