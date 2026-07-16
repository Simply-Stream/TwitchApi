<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\StreamOnlineCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'stream.offline', version: '1', condition: StreamOnlineCondition::class)]
final readonly class StreamOfflineEvent implements EventInterface
{
    /**
     * @param string|null $id                   The id of the stream - a Parameter that Twitch, yet again, just changed without documentation.
     * @param string      $broadcasterUserId    The broadcaster’s user id.
     * @param string      $broadcasterUserLogin The broadcaster’s user login.
     * @param string      $broadcasterUserName  The broadcaster’s user display name.
     */
    public function __construct(
        public ?string $id,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
    ) {
    }
}
