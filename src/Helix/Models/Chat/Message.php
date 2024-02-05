<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Chat;

final readonly class Message
{
    /**
     * @param string            $messageId  The message id for the message that was sent.
     * @param bool              $isSent     If the message passed all checks and was sent.
     * @param DropReason[]|null $dropReason The reason the message was dropped, if any.
     */
    public function __construct(
        private string $messageId,
        private bool $isSent,
        private ?array $dropReason
    ) {
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function isSent(): bool
    {
        return $this->isSent;
    }

    public function getDropReason(): ?array
    {
        return $this->dropReason;
    }
}
