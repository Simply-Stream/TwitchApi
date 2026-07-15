<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\Moderate;

final readonly class Delete
{
    /**
     * @param string $userId      The ID of the user whose message is being deleted.
     * @param string $userLogin   The login of the user.
     * @param string $userName    The user name of the user.
     * @param string $messageId   The ID of the message being deleted.
     * @param string $messageBody The message body of the message being deleted.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $messageId,
        public string $messageBody,
    ) {
    }
}
