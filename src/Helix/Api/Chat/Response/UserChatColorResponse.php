<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Chat\Response;

use SimplyStream\TwitchApi\Helix\Models\Chat\UserChatColor;

final readonly class UserChatColorResponse
{
    /** @param list<UserChatColor> $data */
    public function __construct(
        public array $data,
    ) {
    }
}
