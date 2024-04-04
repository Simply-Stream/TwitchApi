<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\HypeTrain;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class EventData
{
    use SerializesModels;

    /**
     * @param string            $broadcasterId     The ID of the broadcaster that’s running the Hype Train.
     * @param DateTimeInterface $cooldownEndTime   The UTC date and time (in RFC3339 format) that another Hype Train
     *                                             can start.
     * @param DateTimeInterface $expiresAt         The UTC date and time (in RFC3339 format) that the Hype Train ends.
     * @param int               $goal              The value needed to reach the next level.
     * @param string            $id                An ID that identifies this Hype Train.
     * @param Contribution      $lastContribution  The most recent contribution towards the Hype Train’s goal.
     * @param int               $level             The highest level that the Hype Train reached (the levels are 1
     *                                             through 5).
     * @param DateTimeInterface $startedAt         The UTC date and time (in RFC3339 format) that this Hype Train
     *                                             started.
     * @param Contribution[]    $topContributions  The top contributors for each contribution type. For example, the
     *                                             top contributor using BITS (by aggregate) and the top contributor
     *                                             using SUBS (by count).
     * @param int               $total             The current total amount raised.
     *
     */
    public function __construct(
        private string $broadcasterId,
        private DateTimeInterface $cooldownEndTime,
        private DateTimeInterface $expiresAt,
        private int $goal,
        private string $id,
        private Contribution $lastContribution,
        private int $level,
        private DateTimeInterface $startedAt,
        private array $topContributions,
        private int $total,
    ) {
    }

    public function getBroadcasterId(): string
    {
        return $this->broadcasterId;
    }

    public function getCooldownEndTime(): DateTimeInterface
    {
        return $this->cooldownEndTime;
    }

    public function getExpiresAt(): DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function getGoal(): int
    {
        return $this->goal;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLastContribution(): Contribution
    {
        return $this->lastContribution;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getStartedAt(): DateTimeInterface
    {
        return $this->startedAt;
    }

    public function getTopContributions(): array
    {
        return $this->topContributions;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
