<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChannelPointsAutomaticRewardRedemption;

final readonly class RewardMessageFragment
{
    /**
     * @param string                          $text  The message text in fragment.
     * @param string                          $type  The type of message fragment. One of: text, emote.
     * @param RewardMessageFragmentEmote|null $emote Optional. The metadata pertaining to the emote.
     */
    public function __construct(
        public string $text,
        public string $type,
        public ?RewardMessageFragmentEmote $emote = null,
    ) {
    }
}
