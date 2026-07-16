<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Chat\Response;

use SimplyStream\TwitchApi\Helix\Models\Chat\ChatBadge;

final readonly class GlobalChatBadgesResponse
{
    /** @param list<ChatBadge> $data */
    public function __construct(
        public array $data,
    ) {
    }
}
