<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

final readonly class ChannelChatSettingsUpdateEvent extends Event
{
    /**
     * @param string $broadcasterUserId           The ID of the broadcaster specified in the request.
     * @param string $broadcasterUserLogin        The login of the broadcaster specified in the request.
     * @param string $broadcasterUserName         The user name of the broadcaster specified in the request.
     * @param bool   $emoteMode                   A Boolean value that determines whether chat messages must contain
     *                                            only emotes. True if only messages that are 100% emotes are allowed;
     *                                            otherwise false.
     * @param bool   $followerMode                A Boolean value that determines whether the broadcaster restricts the
     *                                            chat room to followers only, based on how long they’ve followed.
     *
     *                                            True if the broadcaster restricts the chat room to followers only;
     *                                            otherwise false.
     *
     *                                            See follower_mode_duration_minutes for how long the followers must
     *                                            have followed the broadcaster to participate in the chat room.
     * @param int    $followerModeDurationMinutes The length of time, in minutes, that the followers must have followed
     *                                            the broadcaster to participate in the chat room. See follower_mode.
     *
     *                                            Null if follower_mode is false.
     * @param bool   $slowMode                    A Boolean value that determines whether the broadcaster limits how
     *                                            often users in the chat room are allowed to send messages.
     *
     *                                            Is true, if the broadcaster applies a delay; otherwise, false.
     *
     *                                            See slow_mode_wait_time_seconds for the delay.
     * @param int    $slowModeWaitTimeSeconds     The amount of time, in seconds, that users need to wait between
     *                                            sending messages. See slow_mode.
     *
     *                                            Null if slow_mode is false.
     * @param bool   $subscriberMode              A Boolean value that determines whether only users that subscribe to
     *                                            the broadcaster’s channel can talk in the chat room.
     *
     *                                            True if the broadcaster restricts the chat room to subscribers only;
     *                                            otherwise false.
     * @param bool   $uniqueChatMode              A Boolean value that determines whether the broadcaster requires
     *                                            users to post only unique messages in the chat room.
     *
     *                                            True if the broadcaster requires unique messages only; otherwise
     *                                            false.
     */
    public function __construct(
        private string $broadcasterUserId,
        private string $broadcasterUserLogin,
        private string $broadcasterUserName,
        private bool $emoteMode,
        private bool $followerMode,
        private int $followerModeDurationMinutes,
        private bool $slowMode,
        private int $slowModeWaitTimeSeconds,
        private bool $subscriberMode,
        private bool $uniqueChatMode,
    ) {
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

    public function isEmoteMode(): bool
    {
        return $this->emoteMode;
    }

    public function isFollowerMode(): bool
    {
        return $this->followerMode;
    }

    public function getFollowerModeDurationMinutes(): int
    {
        return $this->followerModeDurationMinutes;
    }

    public function isSlowMode(): bool
    {
        return $this->slowMode;
    }

    public function getSlowModeWaitTimeSeconds(): int
    {
        return $this->slowModeWaitTimeSeconds;
    }

    public function isSubscriberMode(): bool
    {
        return $this->subscriberMode;
    }

    public function isUniqueChatMode(): bool
    {
        return $this->uniqueChatMode;
    }
}
