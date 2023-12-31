<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class ChannelPointsVoting
{
    use SerializesModels;

    /**
     * @param bool $isEnabled     Indicates if Channel Points can be used for voting.
     * @param int  $amountPerVote Number of Channel Points required to vote once with Channel Points.
     */
    public function __construct(
        private bool $isEnabled,
        private int $amountPerVote
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function getAmountPerVote(): int
    {
        return $this->amountPerVote;
    }
}
