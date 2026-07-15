<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\SuspiciousUser;

use SimplyStream\TwitchApi\EventSub\Shared\MessageFragment;

final readonly class SuspiciousUserMessage
{
    /**
     * @param string            $messageId The UUID that identifies the message.
     * @param string            $text      The chat message in plain text.
     * @param MessageFragment[] $fragments Ordered list of chat message fragments.
     */
    public function __construct(
        public string $messageId,
        public string $text,
        public array $fragments,
    ) {
    }
}
