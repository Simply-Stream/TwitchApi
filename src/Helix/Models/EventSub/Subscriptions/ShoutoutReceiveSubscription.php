<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ShoutoutReceiveCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * Sends a notification when the specified broadcaster receives a Shoutout.
 */
final readonly class ShoutoutReceiveSubscription extends Subscription
{
    public const TYPE = 'channel.shoutout.receive';

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
            new ShoutoutReceiveCondition(...$condition),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
