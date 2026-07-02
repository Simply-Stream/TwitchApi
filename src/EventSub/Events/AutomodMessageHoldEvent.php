<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\AutomodMessageHoldCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Shared\Message;

#[EventSubSubscription(type: 'automod.message.hold', version: '2', condition: AutomodMessageHoldCondition::class)]
final readonly class AutomodMessageHoldEvent implements EventInterface
{
    /**
     * @param string             $broadcasterUserId    The ID of the broadcaster specified in the request.
     * @param string             $broadcasterUserLogin The login of the broadcaster specified in the request.
     * @param string             $broadcasterUserName  The user name of the broadcaster specified in the request.
     * @param string             $userId               The message sender’s user ID.
     * @param string             $userLogin            The message sender’s login name.
     * @param string             $userName             The message sender’s display name.
     * @param string             $messageId            The ID of the message that was flagged by automod.
     * @param Message            $message              The body of the message.
     * @param \DateTimeImmutable $heldAt               The timestamp of when automod saved the message.
     * @param string             $reason               Possible values are:
     *                                                 - automod
     *                                                 - blocked_term
     * @param array|null         $automod              Optional. If the message was caught by automod, this will be populated.
     * @param array|null         $blockedTerm          Optional. If the message was caught due to a blocked term, this will be populated.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $messageId,
        public Message $message,
        public \DateTimeImmutable $heldAt,
        public string $reason,
        // TODO: Build class for Automod
        public ?array $automod = null,
        // TODO: Build class for BlockedTerm
        public ?array $blockedTerm = null,
    ) {
    }
}
