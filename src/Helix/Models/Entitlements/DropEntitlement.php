<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Entitlements;

use DateTimeInterface;

final readonly class DropEntitlement
{
    /**
     * @param string            $id                An ID that identifies the entitlement.
     * @param string            $benefitId         An ID that identifies the benefit (reward).
     * @param DateTimeInterface $timestamp         The UTC date and time (in RFC3339 format) of when the entitlement
     *                                             was granted.
     * @param string            $userId            An ID that identifies the user who was granted the entitlement.
     * @param string            $gameId            An ID that identifies the game the user was playing when the reward
     *                                             was entitled.
     * @param string            $fulfillmentStatus The entitlement’s fulfillment status. Possible values are:
     *                                             - CLAIMED
     *                                             - FULFILLED
     * @param DateTimeInterface $lastUpdated       The UTC date and time (in RFC3339 format) of when the entitlement was
     *                                             last updated.
     */
    public function __construct(
        public string $id,
        public string $benefitId,
        public DateTimeInterface $timestamp,
        public string $userId,
        public string $gameId,
        public string $fulfillmentStatus,
        public DateTimeInterface $lastUpdated,
    ) {
    }
}
