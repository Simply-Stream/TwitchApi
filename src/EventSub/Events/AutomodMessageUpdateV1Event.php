<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\AutomodMessageUpdateV1Condition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Shared\Message;

#[EventSubSubscription(type: 'automod.message.update', version: '1', condition: AutomodMessageUpdateV1Condition::class)]
class AutomodMessageUpdateV1Event implements EventInterface
{
    /**
     * @param string             $broadcasterUserId    The ID of the broadcaster specified in the request.
     * @param string             $broadcasterUserLogin The login of the broadcaster specified in the request.
     * @param string             $broadcasterUserName  The user name of the broadcaster specified in the request.
     * @param string             $userId               The message sender’s user ID.
     * @param string             $userLogin            The message sender’s login name.
     * @param string             $userName             The message sender’s display name.
     * @param string             $moderatorUserId      The ID of the moderator.
     * @param string             $moderatorUserLogin   The moderator’s user name.
     * @param string             $moderatorUserName    The login of the moderator.
     * @param string             $messageId            The ID of the message that was flagged by automod.
     * @param Message            $message              The body of the message.
     * @param string             $category             The category of the message.
     * @param int                $level                The level of severity. Measured between 1 to 4.
     * @param string             $status               The message’s status. Possible values are:
     *                                                 - Approved
     *                                                 - Denied
     *                                                 - Expired
     * @param \DateTimeImmutable $heldAt               The timestamp of when automod saved the message.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $moderatorUserId,
        public string $moderatorUserLogin,
        public string $moderatorUserName,
        public string $messageId,
        public Message $message,
        public string $category,
        public int $level,
        public string $status,
        public \DateTimeImmutable $heldAt,
    ) {
    }
}
