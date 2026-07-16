<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Bits;

final readonly class BitsLeaderboard
{
    /**
     * @param string $userId    An ID that identifies a user on the leaderboard.
     * @param string $userLogin The user’s login name.
     * @param string $userName  The user’s display name.
     * @param int    $rank      The user’s position on the leaderboard.
     * @param int    $score     The number of Bits the user has cheered.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
        public int $rank,
        public int $score,
    ) {
    }
}
