<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\DropEntitlementGrantCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * An entitlement for a Drop is granted to a user.
 */
final readonly class DropEntitlementGrantSubscription extends Subscription
{
    public const TYPE = 'drop.entitlement.grant';

    /**
     * @param array{
     *     organizationId: non-empty-string,
     *     categoryId: non-empty-string,
     *     campaignId: non-empty-string
     * }                             $condition
     * @param Transport              $transport
     * @param string|null            $id
     * @param string|null            $status
     * @param DateTimeImmutable|null $createdAt
     * @param string|null            $type
     * @param string|null            $version
     */
    public function __construct(
        array $condition,
        Transport $transport,
        ?string $id = null,
        ?string $status = null,
        ?DateTimeImmutable $createdAt = null,
        ?string $type = self::TYPE,
        ?string $version = "1"
    ) {
        parent::__construct(
            $type,
            $version,
            new DropEntitlementGrantCondition(
                $condition['organizationId'],
                $condition['categoryId'],
                $condition['campaignId']
            ),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
