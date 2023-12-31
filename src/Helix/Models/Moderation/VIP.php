<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class VIP
{
    use SerializesModels;

    /**
     * @param string $userId    An ID that uniquely identifies the VIP user.
     * @param string $userLogin The user’s login name.
     * @param string $userName  The user’s display name.
     */
    public function __construct(
        private string $userId,
        private string $userLogin,
        private string $userName,
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

    public function getUserName(): string
    {
        return $this->userName;
    }
}
