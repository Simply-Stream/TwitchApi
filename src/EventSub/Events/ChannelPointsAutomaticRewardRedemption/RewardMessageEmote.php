<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChannelPointsAutomaticRewardRedemption;

final readonly class RewardMessageEmote
{
    /**
     * @param string $id    The emote ID.
     * @param int    $begin The index of where the Emote starts in the text.
     * @param int    $end   The index of where the Emote ends in the text.
     */
    public function __construct(
        public string $id,
        public int $begin,
        public int $end,
    ) {
    }
}
