<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

final readonly class AutoModStatus
{
    /**
     * @param string $msgId       The caller-defined ID passed in the request.
     * @param bool   $isPermitted A Boolean value that indicates whether Twitch would approve the message for chat or
     *                            hold it for moderator review or block it from chat.
     */
    public function __construct(
        public string $msgId,
        public bool $isPermitted,
    ) {
    }
}
