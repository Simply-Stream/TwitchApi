<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\UserAuthorizationRevokeCondition;

#[EventSubSubscription(type: 'user.authorization.revoke', version: '1', condition: UserAuthorizationRevokeCondition::class)]
final readonly class UserAuthorizationRevokeEvent
{
    /**
     * @param string      $clientId  The client_id of the application with revoked user access.
     * @param string      $userId    The user id for the user who has revoked authorization for your client id.
     * @param string|null $userLogin The user login for the user who has revoked authorization for your client id. This
     *                               is null if the user no longer exists.
     * @param string|null $userName  The user display name for the user who has revoked authorization for your client
     *                               id. This is null if the user no longer exists.
     */
    public function __construct(
        public string $clientId,
        public string $userId,
        public ?string $userLogin = null,
        public ?string $userName = null
    ) {
    }
}
