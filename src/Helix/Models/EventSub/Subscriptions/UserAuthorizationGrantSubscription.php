<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\UserAuthorizationGrantCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * A user’s authorization has been granted to your client id.
 */
final readonly class UserAuthorizationGrantSubscription extends Subscription
{
    public const TYPE = 'user.authorization.grant';

    /**
     * @param array{clientId: non-empty-string} $condition
     * @param Transport                         $transport
     * @param string|null                       $id
     * @param string|null                       $status
     * @param DateTimeImmutable|null            $createdAt
     * @param string|null                       $type
     * @param string|null                       $version
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
            new UserAuthorizationGrantCondition($condition['clientId']),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
