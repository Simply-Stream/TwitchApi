<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelPredictionBeginCondition;
use SimplyStream\TwitchApi\Helix\Models\Predictions\Outcome;

#[EventSubSubscription(type: 'channel.prediction.begin', version: '1', condition: ChannelPredictionBeginCondition::class)]
final readonly class ChannelPredictionBeginEvent
{
    /**
     * @param string            $id                   Channel Points Prediction ID.
     * @param string            $broadcasterUserId    The requested broadcaster ID.
     * @param string            $broadcasterUserLogin The requested broadcaster login.
     * @param string            $broadcasterUserName  The requested broadcaster display name.
     * @param string            $title                Title for the Channel Points Prediction.
     * @param Outcome[]         $outcomes             An array of outcomes for the Channel Points Prediction.
     * @param DateTimeInterface $startedAt            The time the Channel Points Prediction started.
     * @param DateTimeInterface $locksAt              The time the Channel Points Prediction will automatically lock.
     */
    public function __construct(
        public string $id,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $title,
        public array $outcomes,
        public DateTimeInterface $startedAt,
        public DateTimeInterface $locksAt
    ) {
    }
}
