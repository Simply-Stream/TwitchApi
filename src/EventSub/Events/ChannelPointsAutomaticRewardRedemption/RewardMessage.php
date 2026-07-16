<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChannelPointsAutomaticRewardRedemption;

final readonly class RewardMessage
{
    /**
     * @param string                   $text      The chat message in plain text.
     * @param RewardMessageFragment[]  $fragments The ordered list of chat message fragments.
     */
    public function __construct(
        public string $text,
        public array $fragments,
    ) {
    }
}
