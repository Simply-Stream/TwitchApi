<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ChannelGuestStarSettingsUpdateCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * (BETA) The host preferences for Guest Star have been updated.
 */
final readonly class ChannelGuestStarSettingsUpdateSubscription extends Subscription
{
    public const TYPE = 'channel.guest_star_settings.update';

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
            new ChannelGuestStarSettingsUpdateCondition($condition['broadcasterUserId'], $condition['moderatorUserId']),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
