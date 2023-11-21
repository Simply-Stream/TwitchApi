<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ChannelFollowCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * A specified channel receives a follow.
 */
final readonly class ChannelFollowSubscription extends Subscription
{
    public const TYPE = 'channel.follow';

    public function __construct(
        array $condition,
        Transport $transport,
        ?string $id = null,
        ?string $status = null,
        ?DateTimeImmutable $createdAt = null,
        ?string $type = self::TYPE,
        ?string $version = "2"
    ) {
        parent::__construct(
            $type,
            $version,
            new ChannelFollowCondition(...$condition),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
