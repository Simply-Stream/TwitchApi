<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\GoalBeginCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.goal.begin', version: '1', condition: GoalBeginCondition::class)]
final readonly class GoalBeginEvent implements EventInterface
{
    /**
     * @param string            $id                   An ID that identifies this event.
     * @param string            $broadcasterUserId    An ID that uniquely identifies the broadcaster.
     * @param string            $broadcasterUserName  The broadcaster’s display name.
     * @param string            $broadcasterUserLogin The broadcaster’s user handle.
     * @param string            $type                 The type of goal. One of: follow, subscription,
     *                                                subscription_count, new_subscription, new_subscription_count,
     *                                                new_bit, new_cheerer.
     * @param string            $description          A description of the goal, if specified. Max 40 characters.
     * @param int               $currentAmount        The goal’s current value.
     * @param int               $targetAmount         The goal’s target value.
     * @param DateTimeInterface $startedAt            The UTC timestamp (RFC3339) indicating when the broadcaster
     *                                                created the goal.
     */
    public function __construct(
        public string $id,
        public string $broadcasterUserId,
        public string $broadcasterUserName,
        public string $broadcasterUserLogin,
        public string $type,
        public string $description,
        public int $currentAmount,
        public int $targetAmount,
        public DateTimeInterface $startedAt,
    ) {
    }
}
