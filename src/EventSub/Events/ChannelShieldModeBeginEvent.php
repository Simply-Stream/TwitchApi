<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelShieldModeBeginCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.shield_mode.begin', version: '1', condition: ChannelShieldModeBeginCondition::class)]
final readonly class ChannelShieldModeBeginEvent implements EventInterface
{
    /**
     * @param string            $broadcasterUserId    An ID that identifies the broadcaster whose Shield Mode status
     *                                                was updated.
     * @param string            $broadcasterUserLogin The broadcaster’s login name.
     * @param string            $broadcasterUserName  The broadcaster’s display name.
     * @param string            $moderatorUserId      An ID that identifies the moderator that updated the Shield
     *                                                Mode’s status. If the broadcaster updated the status, this ID
     *                                                will be the same as broadcasterUserId.
     * @param string            $moderatorUserLogin   The moderator’s login name.
     * @param string            $moderatorUserName    The moderator’s display name.
     * @param DateTimeInterface $startedAt            The UTC timestamp (RFC3339) of when the moderator activated
     *                                                Shield Mode.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $moderatorUserId,
        public string $moderatorUserLogin,
        public string $moderatorUserName,
        public DateTimeInterface $startedAt,
    ) {
    }
}
