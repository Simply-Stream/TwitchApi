<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Chat\Response;

use SimplyStream\TwitchApi\Helix\Models\Chat\Message;

final readonly class SendChatMessageResponse
{
    /** @param list<Message> $data */
    public function __construct(
        public array $data,
    ) {
    }
}
