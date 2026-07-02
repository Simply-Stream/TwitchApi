<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelPredictionLockCondition;
use SimplyStream\TwitchApi\Helix\Models\Predictions\Outcome;

#[EventSubSubscription(type: 'channel.prediction.lock', version: '1', condition: ChannelPredictionLockCondition::class)]
final readonly class ChannelPredictionLockEvent
{
    /**
     * @param string            $id                    Channel Points Prediction ID.
     * @param string            $broadcasterUserId     The requested broadcaster ID.
     * @param string            $broadcasterUserLogin  The requested broadcaster login.
     * @param string            $broadcasterUserName   The requested broadcaster display name.
     * @param string            $title                 Title for the Channel Points Prediction.
     * @param Outcome[]         $outcomes              An array of outcomes for the Channel Points Prediction. Includes
     *                                                 top_predictors.
     * @param DateTimeInterface $startedAt             The time the Channel Points Prediction started.
     * @param DateTimeInterface $lockedAt              The time the Channel Points Prediction was locked.
     */
    public function __construct(
        public string $id,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $title,
        public array $outcomes,
        public DateTimeInterface $startedAt,
        public DateTimeInterface $lockedAt
    ) {
    }
}
