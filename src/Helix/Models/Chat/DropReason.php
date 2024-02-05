<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Chat;

final readonly class DropReason
{
    /**
     * @param string $code    Code for why the message was dropped.
     * @param string $message Message for why the message was dropped.
     */
    public function __construct(
        private string $code,
        private string $message
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
