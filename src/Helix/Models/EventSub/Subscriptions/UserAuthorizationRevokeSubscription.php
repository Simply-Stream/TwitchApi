<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\UserAuthorizationRevokeCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * A user’s authorization has been revoked for your client id.
 */
final readonly class UserAuthorizationRevokeSubscription extends Subscription
{
    public const TYPE = 'user.authorization.revoke';

    /**
     * @param array{clientId: non-empty-string} $condition
     * @param Transport                         $transport
     * @param string|null                       $id
     * @param string|null                       $status
     * @param DateTimeInterface|null            $createdAt
     * @param string|null                       $type
     * @param string|null                       $version
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
            new UserAuthorizationRevokeCondition($condition['clientId']),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
