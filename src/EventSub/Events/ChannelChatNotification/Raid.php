<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChannelChatNotification;

final readonly class Raid
{
    /**
     * @param string $userId          The user ID of the broadcaster raiding this channel.
     * @param string $userName        The user name of the broadcaster raiding this channel.
     * @param string $userLogin       The login name of the broadcaster raiding this channel.
     * @param int    $viewerCount     The number of viewers raiding this channel from the broadcaster’s channel.
     * @param string $profileImageUrl Profile image URL of the broadcaster raiding this channel.
     */
    public function __construct(
        public string $userId,
        public string $userName,
        public string $userLogin,
        public int $viewerCount,
        public string $profileImageUrl
    ) {
    }
}
