<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Chat;

final readonly class Chatter
{
    /**
     * @param string $userId    The ID of a user that’s connected to the broadcaster’s chat room.
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
