<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Request;

use SimplyStream\TwitchApi\Helix\Models\Moderation\BanUser;

final readonly class BanUserRequest
{
    /**
     * @param string  $broadcasterId The ID of the broadcaster whose chat room the user is being banned from.
     * @param string  $moderatorId   The ID of the broadcaster or a user that has permission to moderate the
     *                              broadcaster’s chat room. This ID must match the user ID in the user access token.
     * @param BanUser $ban           The ban to apply (user id, optional duration and reason).
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
        public BanUser $ban,
    ) {}
}
