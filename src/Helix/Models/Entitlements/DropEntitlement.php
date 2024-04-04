<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Entitlements;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class DropEntitlement
{
    use SerializesModels;

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
     * @param DateTimeInterface $lastUpdated       The UTC date and time (in RFC3339 format) of when the entitlement
     *                                             was last updated.
     */
    public function __construct(
        private string $id,
        private string $benefitId,
        private DateTimeInterface $timestamp,
        private string $userId,
        private string $gameId,
        private string $fulfillmentStatus,
        private DateTimeInterface $lastUpdated
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBenefitId(): string
    {
        return $this->benefitId;
    }

    public function getTimestamp(): DateTimeInterface
    {
        return $this->timestamp;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getGameId(): string
    {
        return $this->gameId;
    }

    public function getFulfillmentStatus(): string
    {
        return $this->fulfillmentStatus;
    }

    public function getLastUpdated(): DateTimeInterface
    {
        return $this->lastUpdated;
    }
}
