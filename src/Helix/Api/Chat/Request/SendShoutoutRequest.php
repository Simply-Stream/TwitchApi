<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Chat\Request;

final readonly class SendShoutoutRequest
{
    /**
     * @param string $fromBroadcasterId The ID of the broadcaster that’s sending the Shoutout.
     * @param string $toBroadcasterId   The ID of the broadcaster that’s receiving the Shoutout.
     * @param string $moderatorId       The ID of the broadcaster or a user that is one of the broadcaster’s moderators.
     *                                 This ID must match the user ID in the access token.
     */
    public function __construct(
        public string $fromBroadcasterId,
        public string $toBroadcasterId,
        public string $moderatorId,
    ) {
    }
}
