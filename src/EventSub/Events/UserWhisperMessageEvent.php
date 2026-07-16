<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\UserWhisperMessageCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Events\UserWhisperMessage\Whisper;

#[EventSubSubscription(type: 'user.whisper.message', version: '1', condition: UserWhisperMessageCondition::class)]
final readonly class UserWhisperMessageEvent implements EventInterface
{
    /**
     * @param string  $fromUserId    The ID of the user sending the message.
     * @param string  $fromUserName  The name of the user sending the message.
     * @param string  $fromUserLogin The login of the user sending the message.
     * @param string  $toUserId      The ID of the user receiving the message.
     * @param string  $toUserName    The name of the user receiving the message.
     * @param string  $toUserLogin   The login of the user receiving the message.
     * @param string  $whisperId     The whisper ID.
     * @param Whisper $whisper        Object containing whisper information.
     */
    public function __construct(
        public string $fromUserId,
        public string $fromUserName,
        public string $fromUserLogin,
        public string $toUserId,
        public string $toUserName,
        public string $toUserLogin,
        public string $whisperId,
        public Whisper $whisper,
    ) {
    }
}
