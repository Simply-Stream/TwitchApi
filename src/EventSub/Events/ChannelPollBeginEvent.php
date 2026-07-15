<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelPollBeginCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Events\Poll\BitsVoting;
use SimplyStream\TwitchApi\Helix\Models\Polls\Choice;

#[EventSubSubscription(type: 'channel.poll.begin', version: '1', condition: ChannelPollBeginCondition::class)]
final readonly class ChannelPollBeginEvent implements EventInterface
{
    /**
     * @param string              $id                   ID of the poll.
     * @param string              $broadcasterUserId    The requested broadcaster ID.
     * @param string              $broadcasterUserLogin The requested broadcaster login.
     * @param string              $broadcasterUserName  The requested broadcaster display name.
     * @param string              $title                Question displayed for the poll.
     * @param Choice[]            $choices              An array of choices for the poll.
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
