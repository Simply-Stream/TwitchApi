<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

final readonly class ChannelPointsVoting
{
    /**
     * @param bool $isEnabled     Indicates if Channel Points can be used for voting.
     * @param int  $amountPerVote Number of Channel Points required to vote once with Channel Points.
     */
    public function __construct(
        public bool $isEnabled,
        public int $amountPerVote
    ) {
    }
}
