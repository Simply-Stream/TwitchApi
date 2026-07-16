<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Shared;

final readonly class Mention
{
    /**
     * @param string $userId    The user ID of the mentioned user.
     * @param string $userName  The user name of the mentioned user.
     * @param string $userLogin The user login of the mentioned user.
     */
    public function __construct(
        public string $userId,
        public string $userName,
        public string $userLogin
    ) {
    }
}
