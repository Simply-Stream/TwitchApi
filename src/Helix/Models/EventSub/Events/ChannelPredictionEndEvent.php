<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\Predictions\Outcome;

final readonly class ChannelPredictionEndEvent extends Event
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
        private string $id,
        private string $broadcasterUserId,
        private string $broadcasterUserLogin,
        private string $broadcasterUserName,
        private string $title,
        private string $winningOutcomeId,
        private array $outcomes,
        private string $status,
        private DateTimeInterface $startedAt,
        private DateTimeInterface $endedAt
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBroadcasterUserId(): string
    {
        return $this->broadcasterUserId;
    }

    public function getBroadcasterUserLogin(): string
    {
        return $this->broadcasterUserLogin;
    }

    public function getBroadcasterUserName(): string
    {
        return $this->broadcasterUserName;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getWinningOutcomeId(): string
    {
        return $this->winningOutcomeId;
    }

    public function getOutcomes(): array
    {
        return $this->outcomes;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getStartedAt(): DateTimeInterface
    {
        return $this->startedAt;
    }

    public function getEndedAt(): DateTimeInterface
    {
        return $this->endedAt;
    }
}
