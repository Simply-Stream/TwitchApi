<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\HypeTrainEndCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Shared\Contribution;
use SimplyStream\TwitchApi\Helix\Models\HypeTrain\SharedTrainParticipant;

#[EventSubSubscription(type: 'channel.hype_train.end', version: '2', condition: HypeTrainEndCondition::class)]
final readonly class HypeTrainEndEvent implements EventInterface
{
    /**
     * @param string                         $id                      The Hype Train ID.
     * @param string                         $broadcasterUserId       The requested broadcaster ID.
     * @param string                         $broadcasterUserLogin    The requested broadcaster login.
     * @param string                         $broadcasterUserName     The requested broadcaster display name.
     * @param int                            $total                   Total points contributed to the Hype Train.
     * @param Contribution[]                 $topContributions        The contributors with the most points
     *                                                                contributed.
     * @param int                            $level                   The final level of the Hype Train.
     * @param SharedTrainParticipant[]|null  $sharedTrainParticipants Non-null for a shared Hype Train. Contains the
     *                                                                list of broadcasters in the shared Hype Train.
     * @param DateTimeInterface              $startedAt               The time when the Hype Train started.
     * @param DateTimeInterface              $cooldownEndsAt          The time when the Hype Train cooldown ends so
     *                                                                that the next Hype Train can start.
     * @param DateTimeInterface              $endedAt                 The time when the Hype Train ended.
     * @param string                         $type                    The type of the Hype Train. One of: treasure,
     *                                                                golden_kappa, regular.
     * @param bool                           $isSharedTrain           Indicates if the Hype Train is shared.
     */
    public function __construct(
        public string $id,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public int $total,
        public array $topContributions,
        public int $level,
        public ?array $sharedTrainParticipants,
        public DateTimeInterface $startedAt,
        public DateTimeInterface $cooldownEndsAt,
        public DateTimeInterface $endedAt,
        public string $type,
        public bool $isSharedTrain,
    ) {
    }
}
