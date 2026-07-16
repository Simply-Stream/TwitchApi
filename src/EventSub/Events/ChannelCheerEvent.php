<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelCheerCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.cheer', version: '1', condition: ChannelCheerCondition::class)]
final readonly class ChannelCheerEvent implements EventInterface
{
    /**
     * @param bool        $isAnonymous          Whether the user cheered anonymously or not.
     * @param string      $broadcasterUserId    The requested broadcaster ID.
     * @param string      $broadcasterUserLogin The requested broadcaster login.
     * @param string      $broadcasterUserName  The requested broadcaster display name.
     * @param string      $message              The message sent with the cheer.
     * @param int         $bits                 The number of bits cheered.
     * @param string|null $userId               The user ID for the user who cheered on the specified channel. This is
     *                                          null if is_anonymous is true.
     * @param string|null $userLogin            The user login for the user who cheered on the specified channel. This
     *                                          is null if is_anonymous is true.
     * @param string|null $userName             The user display name for the user who cheered on the specified
     *                                          channel. This is null if is_anonymous is true.
     */
    public function __construct(
        public bool $isAnonymous,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $message,
        public int $bits,
        public ?string $userId = null,
        public ?string $userLogin = null,
        public ?string $userName = null,
    ) {
    }
}
