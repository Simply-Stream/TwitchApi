<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChannelPointsAutomaticRewardRedemption;

final readonly class RewardMessageV1
{
    /**
     * @param string               $text   The text of the chat message.
     * @param RewardMessageEmote[]  $emotes An array that includes the emote ID and start/end positions for where the
     *                                     emote appears in the text.
     */
    public function __construct(
        public string $text,
        public array $emotes,
    ) {
    }
}
