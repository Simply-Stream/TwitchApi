<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelPredictionEndCondition;
use SimplyStream\TwitchApi\Helix\Models\Predictions\Outcome;

#[EventSubSubscription(type: 'channel.prediction.end', version: '1', condition: ChannelPredictionEndCondition::class)]
final readonly class ChannelPredictionEndEvent
{
    /**
     * @param string            $id                    Channel Points Prediction ID.
     * @param string            $broadcasterUserId     The requested broadcaster ID.
     * @param string            $broadcasterUserLogin  The requested broadcaster login.
     * @param string            $broadcasterUserName   The requested broadcaster display name.
     * @param string            $title                 Title for the Channel Points Prediction.
     * @param string            $winningOutcomeId      ID of the winning outcome.
     * @param Outcome[]         $outcomes              An array of outcomes for the Channel Points Prediction. Includes
     *                                                 top_predictors.
     * @param string            $status                The status of the Channel Points Prediction. Valid values are
     *                                                 resolved and canceled.
     * @param DateTimeInterface $startedAt             The time the Channel Points Prediction started.
     * @param DateTimeInterface $endedAt               The time the Channel Points Prediction ended.
     */
    public function __construct(
        public string $id,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $title,
        public string $winningOutcomeId,
        public array $outcomes,
        public string $status,
        public DateTimeInterface $startedAt,
        public DateTimeInterface $endedAt
    ) {
    }
}
