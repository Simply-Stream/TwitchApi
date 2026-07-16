<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

final readonly class ModeratedChannel
{
    /**
     * @param string $broadcasterId    An ID that uniquely identifies the channel this user can moderate.
     * @param string $broadcasterLogin The channel’s login name.
     * @param string $broadcasterName  The channel’s display name.
     */
    public function __construct(
        public string $broadcasterId,
        public string $broadcasterLogin,
        public string $broadcasterName,
    ) {
    }
}
