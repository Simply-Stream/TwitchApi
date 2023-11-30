<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ChannelPointsCustomRewardUpdateCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * A custom channel points reward has been updated for the specified channel.
 */
final readonly class ChannelPointsCustomRewardUpdateSubscription extends Subscription
{
    public const TYPE = 'channel.channel_points_custom_reward.update';

    /**
     * @param array{broadcasterUserId: non-empty-string, rewardId: non-empty-string|null} $condition
     * @param Transport                                                                   $transport
     * @param string|null                                                                 $id
     * @param string|null                                                                 $status
     * @param DateTimeImmutable|null                                                      $createdAt
     * @param string|null                                                                 $type
     * @param string|null                                                                 $version
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
            new ChannelPointsCustomRewardUpdateCondition(
                $condition['broadcasterUserId'],
                $condition['rewardId'] ?? null
            ),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
