<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ShieldModeBeginCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.shield_mode.begin', version: '1', condition: ShieldModeBeginCondition::class)]
final readonly class ShieldModeEvent implements EventInterface
{
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $moderatorUserId,
        public string $moderatorUserLogin,
        public string $moderatorUserName,
        public ?\DateTimeInterface $startedAt = null,
        public ?\DateTimeInterface $endedAt = null
    ) {
    }
}
