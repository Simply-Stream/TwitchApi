<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ChannelGuestStarGuestUpdateCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * (BETA) A guest or a slot is updated in an active Guest Star session.
 */
final readonly class ChannelGuestStarGuestUpdateSubscription extends Subscription
{
    public const TYPE = 'channel.guest_star_guest.update';

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
        ?string $version = "beta"
    ) {
        parent::__construct(
            $type,
            $version,
            new ChannelGuestStarGuestUpdateCondition($condition['broadcasterUserId'], $condition['moderatorUserId']),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
