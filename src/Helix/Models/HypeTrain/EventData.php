<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\HypeTrain;

use DateTimeInterface;

final readonly class EventData
{
    /**
     * @param string            $broadcasterId    The ID of the broadcaster that’s running the Hype Train.
     * @param DateTimeInterface $cooldownEndTime  The UTC date and time (in RFC3339 format) that another Hype Train can
     *                                            start.
     * @param DateTimeInterface $expiresAt        The UTC date and time (in RFC3339 format) that the Hype Train ends.
     * @param int               $goal             The value needed to reach the next level.
     * @param string            $id               An ID that identifies this Hype Train.
     * @param Contribution      $lastContribution The most recent contribution towards the Hype Train’s goal.
     * @param int               $level            The highest level that the Hype Train reached (the levels are 1
     *                                            through 5).
     * @param DateTimeInterface $startedAt        The UTC date and time (in RFC3339 format) that this Hype Train
     *                                            started.
     * @param list<Contribution> $topContributions The top contributors for each contribution type. For example, the
     *                                            top contributor using BITS (by aggregate) and the top contributor
     *                                            using SUBS (by count).
     * @param int               $total            The current total amount raised.
     */
    public function __construct(
        public string $broadcasterId,
        public DateTimeInterface $cooldownEndTime,
        public DateTimeInterface $expiresAt,
        public int $goal,
        public string $id,
        public Contribution $lastContribution,
        public int $level,
        public DateTimeInterface $startedAt,
        public array $topContributions,
        public int $total,
    ) {
    }
}
