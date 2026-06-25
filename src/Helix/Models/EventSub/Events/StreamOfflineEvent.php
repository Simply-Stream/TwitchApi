<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

final readonly class StreamOfflineEvent extends Event
{
    /**
     * @param string $id The id of the stream - a Parameter that Twitch, yet again, just changed without documentation.
     * @param string $broadcasterUserId    The broadcaster’s user id.
     * @param string $broadcasterUserLogin The broadcaster’s user login.
     * @param string $broadcasterUserName  The broadcaster’s user display name.
     */
    public function __construct(
        private string $id,
        private string $broadcasterUserId,
        private string $broadcasterUserLogin,
        private string $broadcasterUserName,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBroadcasterUserId(): string
    {
        return $this->broadcasterUserId;
    }

    public function getBroadcasterUserLogin(): string
    {
        return $this->broadcasterUserLogin;
    }

    public function getBroadcasterUserName(): string
    {
        return $this->broadcasterUserName;
    }
}
