<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Condition\StreamOnlineCondition;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Transport;

/**
 * The specified broadcaster starts a stream.
 */
final readonly class StreamOnlineSubscription extends Subscription
{
    public const TYPE = 'stream.online';

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
            new StreamOnlineCondition(...$condition),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
