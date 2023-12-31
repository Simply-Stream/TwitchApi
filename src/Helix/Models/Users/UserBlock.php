<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Users;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class UserBlock
{
    use SerializesModels;

    /**
     * @param string $userId      An ID that identifies the blocked user.
     * @param string $userLogin   The blocked user’s login name.
     * @param string $displayName The blocked user’s display name.
     */
    public function __construct(
        private string $userId,
        private string $userLogin,
        private string $displayName
    ) {
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getUserLogin(): string
    {
        return $this->userLogin;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }
}
