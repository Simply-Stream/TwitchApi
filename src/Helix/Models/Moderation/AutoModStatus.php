<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class AutoModStatus
{
    use SerializesModels;

    /**
     * @param string $msgId       The caller-defined ID passed in the request.
     * @param bool   $isPermitted A Boolean value that indicates whether Twitch would approve the message for chat or
     *                            hold it for moderator review or block it from chat. Is true if Twitch would approve
     *                            the message; otherwise, false if Twitch would hold the message for moderator review
     *                            or block it from chat.
     */
    public function __construct(
        private string $msgId,
        private bool $isPermitted
    ) {
    }

    public function getMsgId(): string
    {
        return $this->msgId;
    }

    public function isPermitted(): bool
    {
        return $this->isPermitted;
    }
}
