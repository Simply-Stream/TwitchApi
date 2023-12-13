<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Teams;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Member
{
    use SerializesModels;

    /**
     * @param string $userId    An ID that identifies the team member.
     * @param string $userName  The team member’s login name.
     * @param string $userLogin The team member’s display name.
     */
    public function __construct(
        private string $userId,
        private string $userName,
        private string $userLogin
    ) {
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getUserLogin(): string
    {
        return $this->userLogin;
    }
}
