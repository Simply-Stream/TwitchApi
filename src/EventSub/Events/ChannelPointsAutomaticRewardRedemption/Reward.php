<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChannelPointsAutomaticRewardRedemption;

final readonly class Reward
{
    /**
     * @param string             $type          The type of reward. One of: single_message_bypass_sub_mode,
     *                                          send_highlighted_message, random_sub_emote_unlock,
     *                                          chosen_sub_emote_unlock, chosen_modified_sub_emote_unlock.
     * @param int                $channelPoints Number of channel points used.
     * @param UnlockedEmote|null $emote         Optional. Emote associated with the reward.
     */
    public function __construct(
        public string $type,
        public int $channelPoints,
        public ?UnlockedEmote $emote = null,
    ) {
    }
}
