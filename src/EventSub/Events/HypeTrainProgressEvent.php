<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\HypeTrainProgressCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Shared\Contribution;
use SimplyStream\TwitchApi\Helix\Models\HypeTrain\SharedTrainParticipant;

#[EventSubSubscription(type: 'channel.hype_train.progress', version: '2', condition: HypeTrainProgressCondition::class)]
final readonly class HypeTrainProgressEvent implements EventInterface
{
    /**
     * @param string                        $id                      The Hype Train ID.
     * @param string                        $broadcasterUserId       The requested broadcaster ID.
     * @param string                        $broadcasterUserLogin    The requested broadcaster login.
     * @param string                        $broadcasterUserName     The requested broadcaster display name.
     * @param int                           $total                   Total points contributed to the Hype Train.
     * @param int                           $progress                Points contributed at the current level.
     * @param int                           $goal                    Points required to reach the next level.
     * @param Contribution[]                $topContributions        The contributors with the most points contributed.
     * @param int                           $level                   The current level of the Hype Train.
     * @param SharedTrainParticipant[]|null $sharedTrainParticipants Non-null for a shared Hype Train.
     * @param DateTimeInterface             $startedAt               The time when the Hype Train started.
     * @param DateTimeInterface             $expiresAt               The time when the Hype Train expires.
     * @param string                        $type                    One of: treasure, golden_kappa, regular.
     * @param bool                          $isSharedTrain           Indicates if the Hype Train is shared.
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
        public ?array $sharedTrainParticipants,
        public DateTimeInterface $startedAt,
        public DateTimeInterface $expiresAt,
        public string $type,
        public bool $isSharedTrain,
    ) {
    }
}
