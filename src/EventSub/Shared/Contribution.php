<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Shared;

final readonly class Contribution
{
    /**
     * @param string $userId    The ID of the user that made the contribution.
     * @param string $userLogin The user's login name.
     * @param string $userName  The user's display name.
     * @param string $type      The contribution method used. One of: bits, subscription, other.
     * @param int    $total     The total amount contributed. If type is bits, total represents the amount of Bits
     *                         used. If type is subscription, total is 500, 1000, or 2500 to represent tier 1, 2, or
     *                         3 subscriptions, respectively.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $type,
        public int $total,
    ) {
    }
}
