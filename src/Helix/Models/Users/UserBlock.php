<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Users;

final readonly class UserBlock
{
    /**
     * @param string $userId      An ID that identifies the blocked user.
     * @param string $userLogin   The blocked user’s login name.
     * @param string $displayName The blocked user’s display name.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $displayName,
    ) {
    }
}
