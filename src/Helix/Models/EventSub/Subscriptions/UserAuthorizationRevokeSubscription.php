<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\UserAuthorizationRevokeCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * A user’s authorization has been revoked for your client id.
 */
final readonly class UserAuthorizationRevokeSubscription extends Subscription
{
    public const TYPE = 'user.authorization.revoke';

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
            new UserAuthorizationRevokeCondition(...$condition),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
