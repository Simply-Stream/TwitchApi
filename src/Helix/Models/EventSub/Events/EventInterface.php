<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Events;

use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Subscriptions\StreamOnlineSubscription;

interface EventInterface
{
    public const AVAILABLE_EVENTS = [
        StreamOnlineSubscription::TYPE => StreamOnlineEvent::class,
    ];
}
