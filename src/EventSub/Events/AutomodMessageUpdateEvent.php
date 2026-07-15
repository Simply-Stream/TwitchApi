<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\AutomodMessageUpdateCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Events\Automod\BlockedTerm;
use SimplyStream\TwitchApi\EventSub\Events\Automod\CaughtMessage;
use SimplyStream\TwitchApi\EventSub\Shared\Message;

#[EventSubSubscription(type: 'automod.message.update', version: '2', condition: AutomodMessageUpdateCondition::class)]
final readonly class AutomodMessageUpdateEvent implements EventInterface
{
    /**
     * @param string                                                              $broadcasterUserId    The ID of the broadcaster specified in the request.
     * @param string                                                              $broadcasterUserLogin The login of the broadcaster specified in the request.
     * @param string                                                              $broadcasterUserName  The user name of the broadcaster specified in the
     *                                                        request.
     * @param string                                                              $userId               The message sender’s user ID.
     * @param string                                                              $userLogin            The message sender’s login name.
     * @param string                                                              $userName             The message sender’s display name.
     * @param string                                                              $moderatorUserId      The ID of the moderator.
     * @param string                                                              $moderatorUserLogin   The login of the moderator.
     * @param string                                                              $moderatorUserName    The moderator’s user name.
     * @param string                                                              $messageId            The ID of the message that was flagged by automod.
     * @param \SimplyStream\TwitchApi\EventSub\Events\SubscriptionMessage\Message $message              The body of the message.
     * @param string                                                              $status               The message’s status. Possible values are:
     *                                                        - Approved
     *                                                        - Denied
     *                                                        - Expired
     * @param \DateTimeImmutable                                                  $heldAt               The timestamp of when automod saved the message.
     * @param string                                                              $reason               The reason why the message was caught. Possible values
     *                                                        are:
     *                                                        - automod
     *                                                        - blocked_term
     * @param CaughtMessage|null                                                  $automod              Optional. If the message was caught by automod, this
     *                                                        will be populated.
     * @param BlockedTerm|null   $blockedTerm          Optional. If the message was caught due to a blocked
     *                                                        term, this will be populated.
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
        public string $status,
        public \DateTimeImmutable $heldAt,
        public string $reason,
        public ?CaughtMessage $automod = null,
        public ?BlockedTerm $blockedTerm = null,
    ) {
    }
}
