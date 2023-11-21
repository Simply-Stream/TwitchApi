<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ChannelModeratorRemoveCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * Moderator privileges were removed from a user on a specified channel.
 */
final readonly class ChannelModeratorRemoveSubscription extends Subscription
{
    public const TYPE = 'channel.moderator.remove';

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
            new ChannelModeratorRemoveCondition(...$condition),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
