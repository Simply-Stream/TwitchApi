<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Predictions;

use DateTimeInterface;

final readonly class Prediction
{
    /**
     * @param string                 $id               An ID that identifies this prediction.
     * @param string                 $broadcasterId    An ID that identifies the broadcaster that created the
     *                                                 prediction.
     * @param string                 $broadcasterName  The broadcaster’s display name.
     * @param string                 $broadcasterLogin The broadcaster’s login name.
     * @param string                 $title            The question that the prediction asks. For example, Will I finish
     *                                                 this entire pizza?
     * @param list<Outcome>          $outcomes         The list of possible outcomes for the prediction.
     * @param int                    $predictionWindow The length of time (in seconds) that the prediction will run for.
     * @param string                 $status           The prediction’s status. Valid values are:
     *                                                 - ACTIVE
     *                                                 - CANCELED
     *                                                 - LOCKED
     *                                                 - RESOLVED
     * @param DateTimeInterface      $createdAt        The UTC date and time of when the Prediction began.
     * @param string|null            $winningOutcomeId The ID of the winning outcome. Is null unless status is RESOLVED.
     * @param DateTimeInterface|null $endedAt          The UTC date and time of when the Prediction ended. If status is
     *                                                 ACTIVE, this is set to null.
     * @param DateTimeInterface|null $lockedAt         The UTC date and time of when the Prediction was locked. If
     *                                                 status is not LOCKED, this is set to null.
     */
    public function __construct(
        public string $id,
        public string $broadcasterId,
        public string $broadcasterName,
        public string $broadcasterLogin,
        public string $title,
        public array $outcomes,
        public int $predictionWindow,
        public string $status,
        public DateTimeInterface $createdAt,
        public ?string $winningOutcomeId = null,
        public ?DateTimeInterface $endedAt = null,
        public ?DateTimeInterface $lockedAt = null,
    ) {
    }
}
