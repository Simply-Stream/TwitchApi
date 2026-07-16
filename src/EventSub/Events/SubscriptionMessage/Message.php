<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\SubscriptionMessage;

final readonly class Message
{
    /**
     * @param string  $text   The text of the resubscription chat message.
     * @param Emote[] $emotes An array that includes the emote ID and start and end positions for where the emote
     *                        appears in the text.
     */
    public function __construct(
        public string $text,
        public array $emotes
    ) {
    }
}
