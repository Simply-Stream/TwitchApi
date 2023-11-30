<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\UserUpdateCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * A user has updated their account.
 */
final readonly class UserUpdateSubscription extends Subscription
{
    public const TYPE = 'user.update';

    /**
     * @param array{userId: non-empty-string} $condition
     * @param Transport                       $transport
     * @param string|null                     $id
     * @param string|null                     $status
     * @param DateTimeImmutable|null          $createdAt
     * @param string|null                     $type
     * @param string|null                     $version
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
            new UserUpdateCondition($condition['userId']),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
