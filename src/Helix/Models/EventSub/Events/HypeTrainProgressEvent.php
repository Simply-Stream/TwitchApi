<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Contribution;

final readonly class HypeTrainProgressEvent extends Event
{
    /**
     * @param string            $id                    The Hype Train ID.
     * @param string            $broadcasterUserId     The requested broadcaster ID.
     * @param string            $broadcasterUserLogin  The requested broadcaster login.
     * @param string            $broadcasterUserName   The requested broadcaster display name.
     * @param int               $total                 Total points contributed to the Hype Train.
     * @param int               $progress              The number of points contributed to the Hype Train at the
     *                                                 current level.
     * @param int               $goal                  The number of points required to reach the next level.
     * @param Contribution[]    $topContributions      The contributors with the most points contributed.
     * @param Contribution      $lastContribution      The most recent contribution.
     * @param int               $level                 The current level of the Hype Train.
     * @param DateTimeInterface $startedAt             The time when the Hype Train started.
     * @param DateTimeInterface $expiresAt             The time when the Hype Train expires. The expiration is extended
     *                                                 when the Hype Train reaches a new level.
     */
    public function __construct(
        private string $id,
        private string $broadcasterUserId,
        private string $broadcasterUserLogin,
        private string $broadcasterUserName,
        private int $total,
        private int $progress,
        private int $goal,
        private array $topContributions,
        private Contribution $lastContribution,
        private int $level,
        private DateTimeInterface $startedAt,
        private DateTimeInterface $expiresAt
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

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getProgress(): int
    {
        return $this->progress;
    }

    public function getGoal(): int
    {
        return $this->goal;
    }

    public function getTopContributions(): array
    {
        return $this->topContributions;
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

    public function getExpiresAt(): DateTimeInterface
    {
        return $this->expiresAt;
    }
}
