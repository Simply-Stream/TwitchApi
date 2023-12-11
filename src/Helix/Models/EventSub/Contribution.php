<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Contribution
{
    use SerializesModels;

    /**
     * @param int    $total     The total amount contributed. If type is bits, total represents the amount of Bits used.
     *                          If type is subscription, total is 500, 1000, or 2500 to represent tier 1, 2, or 3
     *                          subscriptions, respectively.
     * @param string $type      The contribution method used. Possible values are:
     *                          - bits — Cheering with Bits.
     *                          - subscription — Subscription activity like subscribing or gifting subscriptions.
     *                          - other — Covers other contribution methods not listed.
     * @param string $userId    The ID of the user that made the contribution.
     * @param string $userLogin The user’s login name.
     * @param string $userName  The user’s display name.
     */
    public function __construct(
        private int $total,
        private string $type,
        private string $userId,
        private string $userLogin,
        private string $userName
    ) {
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getUserLogin(): string
    {
        return $this->userLogin;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }
}
