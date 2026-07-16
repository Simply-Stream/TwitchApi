<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelSharedChatBeginCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Events\SharedChat\Participant;

#[EventSubSubscription(type: 'channel.shared_chat.begin', version: '1', condition: ChannelSharedChatBeginCondition::class)]
final readonly class ChannelSharedChatBeginEvent implements EventInterface
{
    /**
     * @param string        $sessionId                The unique identifier for the shared chat session.
     * @param string        $broadcasterUserId        The User ID of the channel in the subscription condition which
     *                                                is now active in the shared chat session.
     * @param string        $broadcasterUserName      The display name of the channel in the subscription condition.
     * @param string        $broadcasterUserLogin     The user login of the channel in the subscription condition.
     * @param string        $hostBroadcasterUserId    The User ID of the host channel.
     * @param string        $hostBroadcasterUserName  The display name of the host channel.
     * @param string        $hostBroadcasterUserLogin The user login of the host channel.
     * @param Participant[] $participants             The list of participants in the session.
     */
    public function __construct(
        public string $sessionId,
        public string $broadcasterUserId,
        public string $broadcasterUserName,
        public string $broadcasterUserLogin,
        public string $hostBroadcasterUserId,
        public string $hostBroadcasterUserName,
        public string $hostBroadcasterUserLogin,
        public array $participants,
    ) {
    }
}
