<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\Bits;

final readonly class PowerUp
{
    /**
     * @param string           $type            Possible values: message_effect, celebration, gigantify_an_emote.
     * @param PowerUpEmote|null $emote          Optional. Emote associated with the reward.
     * @param string|null      $messageEffectId Optional. The ID of the message effect.
     */
    public function __construct(
        public string $type,
        public ?PowerUpEmote $emote = null,
        public ?string $messageEffectId = null,
    ) {
    }
}
