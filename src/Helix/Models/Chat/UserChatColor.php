<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Chat;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class UserChatColor
{
    use SerializesModels;

    /**
     * @param string $userId    An ID that uniquely identifies the user.
     * @param string $userLogin The user’s login name.
     * @param string $userName  The user’s display name.
     * @param string $color     The Hex color code that the user uses in chat for their name. If the user hasn’t
     *                          specified a color in their settings, the string is empty.
     */
    public function __construct(
        private string $userId,
        private string $userLogin,
        private string $userName,
        private string $color
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

    public function getColor(): string
    {
        return $this->color;
    }
}
