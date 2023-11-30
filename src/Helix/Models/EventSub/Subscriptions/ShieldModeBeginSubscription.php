<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ShieldModeBeginCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * Sends a notification when the broadcaster activates Shield Mode.
 */
final readonly class ShieldModeBeginSubscription extends Subscription
{
    public const TYPE = 'channel.shield_mode.begin';

    /**
     * @param array{broadcasterUserId: non-empty-string, moderatorUserId: non-empty-string} $condition
     * @param Transport                                                                     $transport
     * @param string|null                                                                   $id
     * @param string|null                                                                   $status
     * @param DateTimeImmutable|null                                                        $createdAt
     * @param string|null                                                                   $type
     * @param string|null                                                                   $version
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
            new ShieldModeBeginCondition($condition['broadcasterUserId'], $condition['moderatorUserId']),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
