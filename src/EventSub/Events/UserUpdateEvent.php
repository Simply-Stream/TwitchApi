<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\UserUpdateCondition;

#[EventSubSubscription(type: 'user.update', version: '1', condition: UserUpdateCondition::class)]
final readonly class UserUpdateEvent
{
    /**
     * @param string      $userId        The user’s user id.
     * @param string      $userLogin     The user’s user login.
     * @param string      $userName      The user’s user display name.
     * @param bool        $emailVerified A Boolean value that determines whether Twitch has verified the user’s email
     *                                   address. Is true if Twitch has verified the email address; otherwise, false.
     *                                   NOTE: Ignore this field if the email field contains an empty string.
     * @param string      $description   The user’s description.
     * @param string|null $email         The user’s email address. The event includes the user’s email address only if
     *                                   the app used to request this event type includes the user:read:email scope for
     *                                   the user; otherwise, the field is set to an empty string. See Create EventSub
     *                                   Subscription.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
        public bool $emailVerified,
        public string $description,
        public ?string $email = null,
    ) {
    }
}
