<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\HypeTrainBeginCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Contribution;

#[EventSubSubscription(type: 'channel.hype_train.begin', version: '1', condition: HypeTrainBeginCondition::class)]
final readonly class HypeTrainBeginEvent
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
     * @param int               $level                 The starting level of the Hype Train.
     * @param DateTimeInterface $startedAt             The time when the Hype Train started.
     * @param DateTimeInterface $expiresAt             The time when the Hype Train expires. The expiration is extended
     *                                                 when the Hype Train reaches a new level.
     */
    public function __construct(
        public string $id,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public int $total,
        public int $progress,
        public int $goal,
        public array $topContributions,
        public Contribution $lastContribution,
        public int $level,
        public DateTimeInterface $startedAt,
        public DateTimeInterface $expiresAt
    ) {
    }
}
