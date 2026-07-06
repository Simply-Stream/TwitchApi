<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Subscriptions\Request;

final readonly class CheckUserSubscriptionRequest
{
    /**
     * @param string $broadcasterId The ID of a partner or affiliate broadcaster.
     * @param string $userId        The ID of the user that you’re checking to see whether they subscribe to the
     *                             broadcaster in broadcaster_id. This ID must match the user ID in the access token.
     */
    public function __construct(
        public string $broadcasterId,
        public string $userId,
    ) {}
}
