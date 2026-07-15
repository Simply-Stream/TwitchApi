<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\Poll;

final readonly class BitsVoting
{
    /**
     * @param bool $isEnabled     Not used; will be set to false.
     * @param int  $amountPerVote Not used; will be set to 0.
     */
    public function __construct(
        public bool $isEnabled = false,
        public int $amountPerVote = 0
    ) {
    }
}
