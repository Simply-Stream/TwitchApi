<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Chat;

final readonly class Message
{
    /**
     * @param string          $messageId  The message id for the message that was sent.
     * @param bool            $isSent     If the message passed all checks and was sent.
     * @param DropReason|null $dropReason The reason the message was dropped, if any. Null if the message was sent.
     */
    public function __construct(
        public string $messageId,
        public bool $isSent,
        public ?DropReason $dropReason = null,
    ) {
    }
}
