<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ChannelRaidCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * A broadcaster raids another broadcaster’s channel.
 */
final readonly class ChannelRaidSubscription extends Subscription
{
    public const TYPE = 'channel.raid';

    /**
     * @param array{fromBroadcasterUserId: non-empty-string|null, toBroadcasterUserId: non-empty-string|null} $condition
     * @param Transport                                                                                       $transport
     * @param string|null                                                                                     $id
     * @param string|null                                                                                     $status
     * @param DateTimeInterface|null                                                                          $createdAt
     * @param string|null                                                                                     $type
     * @param string|null                                                                                     $version
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
            new ChannelRaidCondition(
                $condition['fromBroadcasterUserId'] ?? null,
                $condition['toBroadcasterUserId'] ?? null
            ),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
