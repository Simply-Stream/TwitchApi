<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Condition\ChannelPollProgressCondition;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Transport;

/**
 * Users respond to a poll on a specified channel.
 */
final readonly class ChannelPollProgressSubscription extends Subscription
{
    public const TYPE = 'channel.poll.progress';

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
            new ChannelPollProgressCondition(...$condition),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
