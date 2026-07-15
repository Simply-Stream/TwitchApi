<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelChatUserMessageUpdateCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Shared\Message;

#[EventSubSubscription(type: 'channel.chat.user_message_update', version: '1', condition: ChannelChatUserMessageUpdateCondition::class)]
final readonly class ChannelChatUserMessageUpdateEvent implements EventInterface
{
    /**
     * @param string                                                              $broadcasterUserId    The ID of the broadcaster specified in the request.
     * @param string                                                              $broadcasterUserLogin The login of the broadcaster specified in the request.
     * @param string                                                              $broadcasterUserName  The user name of the broadcaster specified in the request.
     * @param string                                                              $userId               The User ID of the message sender.
     * @param string                                                              $userLogin            The message sender’s login.
     * @param string                                                              $userName             The message sender’s user name.
     * @param string                                                              $status               The message’s status. Possible values are:
     *                                      - approved
     *                                      - denied
     *                                      - invalid
     * @param string                                                              $messageId            The ID of the message that was flagged by automod.
     * @param \SimplyStream\TwitchApi\EventSub\Events\SubscriptionMessage\Message $message              The body of the message.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $status,
        public string $messageId,
        public Message $message,
    ) {
    }
}
