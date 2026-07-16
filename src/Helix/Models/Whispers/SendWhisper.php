<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Whispers;

use Webmozart\Assert\Assert;

final readonly class SendWhisper
{
    private const int MAX_LENGTH = 10_000;

    /**
     * @param string $message The whisper message to send. The message must not be empty.
     *
     *                        The maximum message lengths are:
     *                        - 500 characters if the user you're sending the message to hasn't whispered you before.
     *                        - 10,000 characters if the user you're sending the message to has whispered you before.
     *                        Messages that exceed the maximum length are truncated.
     */
    public function __construct(
        public string $message,
    ) {
        Assert::stringNotEmpty($this->message, 'The whisper message can\'t be empty.');
        Assert::maxLength(
            $this->message,
            self::MAX_LENGTH,
            sprintf('The whisper message can\'t exceed %d characters, got %%s.', self::MAX_LENGTH),
        );
    }
}
