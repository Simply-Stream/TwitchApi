<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Condition\ChannelSubscriptionEndCondition;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Transport;

/**
 * A notification when a subscription to the specified channel ends.
 */
final readonly class ChannelSubscriptionEndSubscription extends Subscription
{
    public const TYPE = 'channel.subscription.end';

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
            new ChannelSubscriptionEndCondition(...$condition),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
