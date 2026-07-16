<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChannelPointsAutomaticRewardRedemption;

final readonly class RewardMessageFragmentEmote
{
    /**
     * @param string $id The ID that uniquely identifies this emote.
     */
    public function __construct(
        public string $id,
    ) {
    }
}
