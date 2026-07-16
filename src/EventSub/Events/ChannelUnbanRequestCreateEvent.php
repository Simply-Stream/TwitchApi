<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelUnbanRequestCreateCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.unban_request.create', version: '1', condition: ChannelUnbanRequestCreateCondition::class)]
final readonly class ChannelUnbanRequestCreateEvent implements EventInterface
{
    /**
     * @param string            $id                   The ID of the unban request.
     * @param string            $broadcasterUserId    The broadcaster’s user ID for the channel the unban request was
     *                                                created for.
     * @param string            $broadcasterUserLogin The broadcaster’s login name.
     * @param string            $broadcasterUserName  The broadcaster’s display name.
     * @param string            $userId               User ID of user that is requesting to be unbanned.
     * @param string            $userLogin            The user’s login name.
     * @param string            $userName             The user’s display name.
     * @param string            $text                 Message sent in the unban request.
     * @param DateTimeInterface $createdAt            The UTC timestamp (in RFC3339 format) of when the unban request
     *                                                was created.
     */
    public function __construct(
        public string $id,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $text,
        public DateTimeInterface $createdAt,
    ) {
    }
}
