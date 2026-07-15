<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChannelPointsAutomaticRewardRedemption;

final readonly class UnlockedEmote
{
    /**
     * @param string $id   The emote ID.
     * @param string $name The human readable emote token.
     */
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }
}
