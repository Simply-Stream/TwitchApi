<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Teams;

final readonly class Member
{
    /**
     * @param string $userId    An ID that identifies the team member.
     * @param string $userName  The team member’s display name.
     * @param string $userLogin The team member’s login name.
     */
    public function __construct(
        public string $userId,
        public string $userName,
        public string $userLogin,
    ) {
    }
}
