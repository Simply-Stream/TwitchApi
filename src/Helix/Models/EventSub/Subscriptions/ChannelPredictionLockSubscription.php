<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ChannelPredictionLockCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * A Prediction was locked on a specified channel.
 */
final readonly class ChannelPredictionLockSubscription extends Subscription
{
    public const TYPE = 'channel.prediction.lock';

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
            new ChannelPredictionLockCondition(...$condition),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
