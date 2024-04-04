<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Predictions;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Prediction
{
    use SerializesModels;

    /**
     * @param string                 $id               An ID that identifies this prediction.
     * @param string                 $broadcasterId    An ID that identifies the broadcaster that created the prediction.
     * @param string                 $broadcasterName  The broadcaster’s display name.
     * @param string                 $broadcasterLogin The broadcaster’s login name.
     * @param string                 $title            The question that the prediction asks. For example, Will I finish
     *                                                 this entire pizza?
     * @param Outcome[]              $outcomes         The list of possible outcomes for the prediction.
     * @param int                    $predictionWindow The length of time (in seconds) that the prediction will run for.
     * @param string                 $status           The prediction’s status. Valid values are:
     *                                                 - ACTIVE — The Prediction is running and viewers can make
     *                                                 predictions.
     *                                                 - CANCELED — The broadcaster canceled the Prediction and refunded
     *                                                 the Channel Points to the participants.
     *                                                 - LOCKED — The broadcaster locked the Prediction, which means
     *                                                 viewers can no longer make predictions.
     *                                                 - RESOLVED — The winning outcome was determined and the Channel
     *                                                 Points were distributed to the viewers who predicted the correct
     *                                                 outcome.
     * @param DateTimeInterface      $createdAt        The UTC date and time of when the Prediction began.
     * @param string|null            $winningOutcomeId The ID of the winning outcome. Is null unless status is RESOLVED.
     * @param DateTimeInterface|null $endedAt          The UTC date and time of when the Prediction ended. If status is
     *                                                 ACTIVE, this is set to null.
     * @param DateTimeInterface|null $lockedAt         The UTC date and time of when the Prediction was locked. If status
     *                                                 is not LOCKED, this is set to null.
     */
    public function __construct(
        private string $id,
        private string $broadcasterId,
        private string $broadcasterName,
        private string $broadcasterLogin,
        private string $title,
        private array $outcomes,
        private int $predictionWindow,
        private string $status,
        private DateTimeInterface $createdAt,
        private ?string $winningOutcomeId = null,
        private ?DateTimeInterface $endedAt = null,
        private ?DateTimeInterface $lockedAt = null,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBroadcasterId(): string
    {
        return $this->broadcasterId;
    }

    public function getBroadcasterName(): string
    {
        return $this->broadcasterName;
    }

    public function getBroadcasterLogin(): string
    {
        return $this->broadcasterLogin;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getOutcomes(): array
    {
        return $this->outcomes;
    }

    public function getPredictionWindow(): int
    {
        return $this->predictionWindow;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getWinningOutcomeId(): ?string
    {
        return $this->winningOutcomeId;
    }

    public function getEndedAt(): ?DateTimeInterface
    {
        return $this->endedAt;
    }

    public function getLockedAt(): ?DateTimeInterface
    {
        return $this->lockedAt;
    }
}
