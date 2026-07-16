<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\UserWhisperMessage;

final readonly class Whisper
{
    /**
     * @param string $text The body of the whisper message.
     */
    public function __construct(
        public string $text,
    ) {
    }
}
