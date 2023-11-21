<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Condition\UserAuthorizationGrantCondition;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Transport;

/**
 * A user’s authorization has been granted to your client id.
 */
final readonly class UserAuthorizationGrantSubscription extends Subscription
{
    public const TYPE = 'user.authorization.grant';

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
            new UserAuthorizationGrantCondition(...$condition),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
