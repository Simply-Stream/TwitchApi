<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;

final readonly class DropEntitlementGrantData
{
    /**
     * @param string            $organizationId  The ID of the organization that owns the game that has Drops enabled.
     * @param string            $categoryId      Twitch category ID of the game that was being played when this benefit
     *                                           was entitled.
     * @param string            $categoryName    The category name.
     * @param string            $campaignId      The campaign this entitlement is associated with.
     * @param string            $userId          Twitch user ID of the user who was granted the entitlement.
     * @param string            $userName        The user display name of the user who was granted the entitlement.
     * @param string            $userLogin       The user login of the user who was granted the entitlement.
     * @param string            $entitlementId   Unique identifier of the entitlement. Use this to de-duplicate
     *                                           entitlements.
     * @param string            $benefitId       Identifier of the Benefit.
     * @param DateTimeInterface $createdAt       UTC timestamp in ISO format when this entitlement was granted on
     *                                           Twitch.
     */
    public function __construct(
        public string $organizationId,
        public string $categoryId,
        public string $categoryName,
        public string $campaignId,
        public string $userId,
        public string $userName,
        public string $userLogin,
        public string $entitlementId,
        public string $benefitId,
        public DateTimeInterface $createdAt
    ) {
    }
}
