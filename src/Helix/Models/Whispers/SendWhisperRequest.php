<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Whispers;

use SimplyStream\TwitchApi\Helix\Models\AbstractModel;

final readonly class SendWhisperRequest extends AbstractModel
{
    /**
     * @param string $message The whisper message to send. The message must not be empty.
     *
     *                        The maximum message lengths are:
     *                        - 500 characters if the user you're sending the message to hasn't whispered you before.
     *                        - 10,000 characters if the user you're sending the message to has whispered you before.
     *                        Messages that exceed the maximum length are truncated.
     */
    public function __construct(
        private string $message
    ) {
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
