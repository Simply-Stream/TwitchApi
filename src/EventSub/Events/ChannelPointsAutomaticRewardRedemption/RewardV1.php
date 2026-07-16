<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChannelPointsAutomaticRewardRedemption;

final readonly class RewardV1
{
    /**
     * @param string             $type          The type of reward. One of: single_message_bypass_sub_mode,
     *                                          send_highlighted_message, random_sub_emote_unlock,
     *                                          chosen_sub_emote_unlock, chosen_modified_sub_emote_unlock,
     *                                          message_effect, gigantify_an_emote, celebration.
     * @param int                $cost          The reward cost.
     * @param UnlockedEmote|null  $unlockedEmote Optional. Emote that was unlocked.
     */
    public function __construct(
        public string $type,
        public int $cost,
        public ?UnlockedEmote $unlockedEmote = null,
    ) {
    }
}
