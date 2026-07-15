<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelWarningSendCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.warning.send', version: '1', condition: ChannelWarningSendCondition::class)]
final readonly class ChannelWarningSendEvent implements EventInterface
{
    /**
     * @param string        $broadcasterUserId    The user ID of the broadcaster.
     * @param string        $broadcasterUserLogin The login of the broadcaster.
     * @param string        $broadcasterUserName  The user name of the broadcaster.
     * @param string        $moderatorUserId      The user ID of the moderator who sent the warning.
     * @param string        $moderatorUserLogin   The login of the moderator.
     * @param string        $moderatorUserName    The user name of the moderator.
     * @param string        $userId               The ID of the user being warned.
     * @param string        $userLogin            The login of the user being warned.
     * @param string        $userName             The user name of the user being warned.
     * @param string|null   $reason               Optional. The reason given for the warning.
     * @param string[]|null $chatRulesCited       Optional. The chat rules cited for the warning.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $moderatorUserId,
        public string $moderatorUserLogin,
        public string $moderatorUserName,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public ?string $reason = null,
        public ?array $chatRulesCited = null,
    ) {
    }
}
