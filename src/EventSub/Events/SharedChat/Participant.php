<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\SharedChat;

final readonly class Participant
{
    /**
     * @param string $broadcasterUserId    The User ID of the participant channel.
     * @param string $broadcasterUserName  The display name of the participant channel.
     * @param string $broadcasterUserLogin The user login of the participant channel.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserName,
        public string $broadcasterUserLogin,
    ) {
    }
}
