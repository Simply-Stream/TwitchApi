<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\HypeTrainProgressCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Contribution;

#[EventSubSubscription(type: 'channel.hype_train.progress', version: '2', condition: HypeTrainProgressCondition::class)]
final readonly class HypeTrainProgressEvent implements EventInterface
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
     * @param int               $level                 The current level of the Hype Train.
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
        public int $level,
        public DateTimeInterface $startedAt,
        public DateTimeInterface $expiresAt
    ) {
    }
}
