<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ChannelPointsCustomRewardRemoveCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * A custom channel points reward has been removed from the specified channel.
 */
final readonly class ChannelPointsCustomRewardRemoveSubscription extends Subscription
{
    public const TYPE = 'channel.channel_points_custom_reward.remove';

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
            new ChannelPointsCustomRewardRemoveCondition(...$condition),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
