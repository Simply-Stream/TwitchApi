<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Channels;

use DateTimeInterface;

final readonly class FollowedChannel
{
    /**
     * @param string            $broadcasterId    An ID that uniquely identifies the broadcaster that this user is
     *                                            following.
     * @param string            $broadcasterLogin The broadcaster’s login name.
     * @param string            $broadcasterName  The broadcaster’s display name.
     * @param DateTimeInterface $followedAt       The UTC timestamp when the user started following the broadcaster.
     */
    public function __construct(
        public string $broadcasterId,
        public string $broadcasterLogin,
        public string $broadcasterName,
        public DateTimeInterface $followedAt,
    ) {
    }
}
