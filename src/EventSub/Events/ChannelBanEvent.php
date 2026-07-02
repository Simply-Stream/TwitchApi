<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelBanCondition;

#[EventSubSubscription(type: 'channel.ban', version: '1', condition: ChannelBanCondition::class)]
final readonly class ChannelBanEvent
{
    /**
     * @param string                 $userId                The user ID for the user who was banned on the specified
     *                                                      channel.
     * @param string                 $userLogin             The user login for the user who was banned on the specified
     *                                                      channel.
     * @param string                 $userName              The user display name for the user who was banned on the
     *                                                      specified channel.
     * @param string                 $broadcasterUserId     The requested broadcaster ID.
     * @param string                 $broadcasterUserLogin  The requested broadcaster login.
     * @param string                 $broadcasterUserName   The requested broadcaster display name.
     * @param string                 $moderatorUserId       The user ID of the issuer of the ban.
     * @param string                 $moderatorUserLogin    The user login of the issuer of the ban.
     * @param string                 $moderatorUserName     The user name of the issuer of the ban.
     * @param string                 $reason                The reason behind the ban.
     * @param DateTimeInterface      $bannedAt              The UTC date and time (in RFC3339 format) of when the user
     *                                                      was banned or put in a timeout.
     * @param bool                   $isPermanent           Indicates whether the ban is permanent (true) or a timeout
     *                                                      (false). If true, ends_at will be null.
     * @param DateTimeInterface|null $endsAt                The UTC date and time (in RFC3339 format) of when the
     *                                                      timeout ends. Is null if the user was banned instead of put
     *                                                      in a timeout.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $moderatorUserId,
        public string $moderatorUserLogin,
        public string $moderatorUserName,
        public string $reason,
        public DateTimeInterface $bannedAt,
        public bool $isPermanent,
        public ?DateTimeInterface $endsAt = null
    ) {
    }
}
