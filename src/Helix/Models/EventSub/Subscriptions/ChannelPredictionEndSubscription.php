<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ChannelPredictionEndCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * A Prediction ended on a specified channel.
 */
final readonly class ChannelPredictionEndSubscription extends Subscription
{
    public const TYPE = 'channel.prediction.end';

    /**
     * @param array{broadcasterUserId: non-empty-string} $condition
     * @param Transport                                  $transport
     * @param string|null                                $id
     * @param string|null                                $status
     * @param DateTimeInterface|null                     $createdAt
     * @param string|null                                $type
     * @param string|null                                $version
     */
    public function __construct(
        array $condition,
        Transport $transport,
        ?string $id = null,
        ?string $status = null,
        ?DateTimeInterface $createdAt = null,
        ?string $type = self::TYPE,
        ?string $version = "1"
    ) {
        parent::__construct(
            $type,
            $version,
            new ChannelPredictionEndCondition($condition['broadcasterUserId']),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
