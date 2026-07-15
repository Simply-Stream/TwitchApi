<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelPollProgressCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Events\Poll\BitsVoting;
use SimplyStream\TwitchApi\EventSub\Events\Poll\ChannelPointsVoting;

#[EventSubSubscription(type: 'channel.poll.progress', version: '1', condition: ChannelPollProgressCondition::class)]
final readonly class ChannelPollProgressEvent implements EventInterface
{
    /**
     * @param string              $id                   ID of the poll.
     * @param string              $broadcasterUserId    The requested broadcaster ID.
     * @param string              $broadcasterUserLogin The requested broadcaster login.
     * @param string              $broadcasterUserName  The requested broadcaster display name.
     * @param string              $title                Question displayed for the poll.
     * @param array               $choices              An array of choices for the poll. Includes vote counts.
     * @param BitsVoting          $bitsVoting           Not supported.
     * @param ChannelPointsVoting $channelPointsVoting  The Channel Points voting settings for the poll.
     * @param DateTimeInterface   $startedAt            The time the poll started.
     * @param DateTimeInterface   $endsAt               The time the poll will end.
     */
    public function __construct(
        public string $id,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $title,
        public array $choices,
        public BitsVoting $bitsVoting,
        public ChannelPointsVoting $channelPointsVoting,
        public DateTimeInterface $startedAt,
        public DateTimeInterface $endsAt
    ) {
    }
}
