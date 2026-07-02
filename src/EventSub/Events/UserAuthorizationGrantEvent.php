<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\UserAuthorizationGrantCondition;

#[EventSubSubscription(type: 'user.authorization.grant', version: '1', condition: UserAuthorizationGrantCondition::class)]
final readonly class UserAuthorizationGrantEvent
{
    /**
     * @param string $clientId  The client_id of the application that was granted user access.
     * @param string $userId    The user id for the user who has granted authorization for your client id.
     * @param string $userLogin The user login for the user who has granted authorization for your client id.
     * @param string $userName  The user display name for the user who has granted authorization for your client id.
     */
    public function __construct(
        public string $clientId,
        public string $userId,
        public string $userLogin,
        public string $userName
    ) {
    }
}
