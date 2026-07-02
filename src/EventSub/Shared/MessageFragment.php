<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Shared;

final readonly class MessageFragment
{
    /**
     * @param string         $text      Message text in fragment
     * @param string|null    $type      The type of message fragment. Possible values:
     *                                  - text
     *                                  - cheermote
     *                                  - emote
     *                                  - mention
     * @param Cheermote|null $cheermote Optional. Metadata pertaining to the cheermote.
     * @param Emote|null     $emote     Optional. Metadata pertaining to the emote.
     * @param Mention|null   $mention   Optional. Metadata pertaining to the mention.
     */
    public function __construct(
        public string $text,
        public ?string $type = null,
        public ?Cheermote $cheermote = null,
        public ?Emote $emote = null,
        public ?Mention $mention = null
    ) {
    }
}
