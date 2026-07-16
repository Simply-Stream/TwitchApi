<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

final readonly class Moderator
{
    /**
     * @param string $userId    The ID of the user that has permission to moderate the broadcaster’s channel.
     * @param string $userLogin The user’s login name.
     * @param string $userName  The user’s display name.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
    ) {
    }
}
