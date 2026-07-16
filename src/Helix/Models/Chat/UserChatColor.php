<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Chat;

final readonly class UserChatColor
{
    /**
     * @param string $userId    An ID that uniquely identifies the user.
     * @param string $userLogin The user’s login name.
     * @param string $userName  The user’s display name.
     * @param string $color     The Hex color code that the user uses in chat for their name. If the user hasn’t
     *                          specified a color in their settings, the string is empty.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $color,
    ) {
    }
}
