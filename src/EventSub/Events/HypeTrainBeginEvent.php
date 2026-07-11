<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\HypeTrainBeginCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Shared\Contribution;
use SimplyStream\TwitchApi\Helix\Models\HypeTrain\SharedTrainParticipant;

#[EventSubSubscription(type: 'channel.hype_train.begin', version: '2', condition: HypeTrainBeginCondition::class)]
final readonly class HypeTrainBeginEvent implements EventInterface
{
    /**
     * @param string                     $id                    The Hype Train ID.
     * @param string                     $broadcasterUserId     The requested broadcaster ID.
     * @param string                     $broadcasterUserLogin  The requested broadcaster login.
     * @param string                     $broadcasterUserName   The requested broadcaster display name.
     * @param int                        $total                 Total points contributed to the Hype Train.
     * @param int                        $progress              The number of points contributed to the Hype Train
     *                                                          at the current level.
     * @param int                        $goal                  The number of points required to reach the next
     *                                                          level.
     * @param Contribution[]             $topContributions      The contributors with the most points contributed.
     * @param int                        $level                 The starting level of the Hype Train.
     * @param int                        $allTimeHighLevel      The all-time high level this type of Hype Train has
     *                                                          reached for this broadcaster.
     * @param int                        $allTimeHighTotal      The all-time high total this type of Hype Train has
     *                                                          reached for this broadcaster.
     * @param SharedTrainParticipant[]|null $sharedTrainParticipants Non-null for a shared Hype Train. Contains the
     *                                                          list of broadcasters in the shared Hype Train.
     * @param DateTimeInterface          $startedAt             The time when the Hype Train started.
     * @param DateTimeInterface          $expiresAt             The time when the Hype Train expires. The expiration
     *                                                          is extended when the Hype Train reaches a new level.
     * @param string                     $type                  The type of the Hype Train. One of: treasure,
     *                                                          golden_kappa, regular.
     * @param bool                       $isSharedTrain         Indicates if the Hype Train is shared.
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
        public int $allTimeHighLevel,
        public int $allTimeHighTotal,
        public ?array $sharedTrainParticipants,
        public DateTimeInterface $startedAt,
        public DateTimeInterface $expiresAt,
        public string $type,
        public bool $isSharedTrain,
    ) {
    }
}
