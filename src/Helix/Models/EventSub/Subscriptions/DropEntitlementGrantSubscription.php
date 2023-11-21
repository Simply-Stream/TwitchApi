<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Condition\DropEntitlementGrantCondition;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Transport;

/**
 * An entitlement for a Drop is granted to a user.
 */
final readonly class DropEntitlementGrantSubscription extends Subscription
{
    public const TYPE = 'drop.entitlement.grant';

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
            new DropEntitlementGrantCondition(...$condition),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
