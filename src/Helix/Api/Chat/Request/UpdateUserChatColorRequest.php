<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Chat\Request;

use SimplyStream\TwitchApi\Helix\Models\Chat\ChatColorEnum;

final readonly class UpdateUserChatColorRequest
{
    /**
     * @param string               $userId The ID of the user whose chat color you want to update. This ID must match
     *                                     the user ID in the access token.
     * @param ChatColorEnum|string $color  The color to use for the user’s name in chat. All users may specify one of
     *                                     the named ChatColorEnum values.
     *
     *                                     Turbo and Prime users may specify a named color or a Hex color code like
     *                                     #9146FF. If you use a Hex color code, remember to URL encode it.
     */
    public function __construct(
        public string $userId,
        public ChatColorEnum|string $color,
    ) {
    }
}
