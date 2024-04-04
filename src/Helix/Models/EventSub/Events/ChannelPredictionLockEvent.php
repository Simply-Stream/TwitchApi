<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\Predictions\Outcome;

final readonly class ChannelPredictionLockEvent extends Event
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
        private string $id,
        private string $broadcasterUserId,
        private string $broadcasterUserLogin,
        private string $broadcasterUserName,
        private string $title,
        private array $outcomes,
        private DateTimeInterface $startedAt,
        private DateTimeInterface $lockedAt
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

    public function getOutcomes(): array
    {
        return $this->outcomes;
    }

    public function getStartedAt(): DateTimeInterface
    {
        return $this->startedAt;
    }

    public function getLockedAt(): DateTimeInterface
    {
        return $this->lockedAt;
    }
}
