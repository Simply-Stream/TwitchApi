<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Chat;

final readonly class ChatSettings
{
    /**
     * @param string    $broadcasterId                 The ID of the broadcaster specified in the request.
     * @param bool      $emoteMode                     A Boolean value that determines whether chat messages must
     *                                                 contain only emotes. Is true if chat messages may contain only
     *                                                 emotes; otherwise, false.
     * @param bool      $followerMode                  A Boolean value that determines whether the broadcaster
     *                                                 restricts the chat room to followers only.
     * @param int|null  $followerModeDuration          The length of time, in minutes, that users must follow the
     *                                                 broadcaster before being able to participate in the chat room.
     *                                                 Is null if follower_mode is false.
     * @param bool      $slowMode                      A Boolean value that determines whether the broadcaster limits
     *                                                 how often users in the chat room are allowed to send messages.
     * @param int|null  $slowModeWaitTime              The amount of time, in seconds, that users must wait between
     *                                                 sending messages. Is null if slow_mode is false.
     * @param bool      $subscriberMode                A Boolean value that determines whether only users that
     *                                                 subscribe to the broadcaster’s channel may talk in the chat
     *                                                 room.
     * @param bool      $uniqueChatMode                A Boolean value that determines whether the broadcaster
     *                                                 requires users to post only unique messages in the chat room.
     * @param string|null $moderatorId                 The moderator’s ID. Included only if the request specifies a
     *                                                 user access token that includes the moderator:read:chat_settings
     *                                                 scope.
     * @param bool|null   $nonModeratorChatDelay       A Boolean value that determines whether the broadcaster adds a
     *                                                 short delay before chat messages appear in the chat room.
     * @param int|null    $nonModeratorChatDelayDuration The amount of time, in seconds, that messages are delayed
     *                                                 before appearing in chat. Is null if non_moderator_chat_delay is
     *                                                 false.
     */
    public function __construct(
        public string $broadcasterId,
        public bool $emoteMode,
        public bool $followerMode,
        public ?int $followerModeDuration,
        public bool $slowMode,
        public ?int $slowModeWaitTime,
        public bool $subscriberMode,
        public bool $uniqueChatMode,
        public ?string $moderatorId = null,
        public ?bool $nonModeratorChatDelay = null,
        public ?int $nonModeratorChatDelayDuration = null,
    ) {
    }
}
