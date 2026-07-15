<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Users\Request;

final readonly class UnblockUserRequest
{
    /**
     * @param string $targetUserId The ID of the user to remove from the broadcaster’s list of blocked users. The API
     *                            ignores the request if the broadcaster hasn’t blocked the user.
     */
    public function __construct(
        public string $targetUserId,
    ) {
    }
}
