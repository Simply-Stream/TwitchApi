<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ChannelFollowCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * A specified channel receives a follow.
 */
final readonly class ChannelFollowSubscription extends Subscription
{
    public const TYPE = 'channel.follow';

    /**
     * @param array{broadcasterUserId: non-empty-string, moderatorUserId: non-empty-string} $condition
     * @param Transport                                                                     $transport
     * @param string|null                                                                   $id
     * @param string|null                                                                   $status
     * @param DateTimeInterface|null                                                        $createdAt
     * @param string|null                                                                   $type
     * @param string|null                                                                   $version
     */
    public function __construct(
        array $condition,
        Transport $transport,
        ?string $id = null,
        ?string $status = null,
        ?DateTimeInterface $createdAt = null,
        ?string $type = self::TYPE,
        ?string $version = "2"
    ) {
        parent::__construct(
            $type,
            $version,
            new ChannelFollowCondition($condition['broadcasterUserId'], $condition['moderatorUserId']),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
