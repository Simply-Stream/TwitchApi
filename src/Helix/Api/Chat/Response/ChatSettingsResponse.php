<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Chat\Response;

use SimplyStream\TwitchApi\Helix\Models\Chat\ChatSettings;

final readonly class ChatSettingsResponse
{
    /** @param list<ChatSettings> $data */
    public function __construct(
        public array $data,
    ) {
    }
}
