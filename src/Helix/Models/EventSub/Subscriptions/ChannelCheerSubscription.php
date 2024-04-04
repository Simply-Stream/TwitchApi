<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ChannelCheerCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * A user cheers on the specified channel.
 */
final readonly class ChannelCheerSubscription extends Subscription
{
    public const TYPE = 'channel.cheer';

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
            new ChannelCheerCondition($condition['broadcasterUserId']),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
