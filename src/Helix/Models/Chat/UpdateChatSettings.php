<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Chat;

use Webmozart\Assert\Assert;

final readonly class UpdateChatSettings
{
    /**
     * @param bool|null $emoteMode                     A Boolean value that determines whether chat messages must
     *                                                 contain only emotes. The default is false.
     * @param bool|null $followerMode                  A Boolean value that determines whether the broadcaster
     *                                                 restricts the chat room to followers only. The default is true.
     * @param int|null  $followerModeDuration          The length of time, in minutes, that users must follow the
     *                                                 broadcaster before being able to participate in the chat room.
     *                                                 Set only if follower_mode is true. Possible values are: 0 (no
     *                                                 restriction) through 129600 (3 months). The default is 0.
     * @param bool|null $nonModeratorChatDelay         A Boolean value that determines whether the broadcaster adds a
     *                                                 short delay before chat messages appear in the chat room. The
     *                                                 default is false.
     * @param int|null  $nonModeratorChatDelayDuration The amount of time, in seconds, that messages are delayed before
     *                                                 appearing in chat. Set only if non_moderator_chat_delay is true.
     *                                                 Possible values are 2, 4, or 6.
     * @param bool|null $slowMode                      A Boolean value that determines whether the broadcaster limits
     *                                                 how often users in the chat room are allowed to send messages.
     *                                                 The default is false.
     * @param int|null  $slowModeWaitTime              The amount of time, in seconds, that users must wait between
     *                                                 sending messages. Set only if slow_mode is true. Possible values
     *                                                 are 3 through 120. The default is 30 seconds.
     * @param bool|null $subscriberMode                A Boolean value that determines whether only users that
     *                                                 subscribe to the broadcaster’s channel may talk in the chat
     *                                                 room. The default is false.
     * @param bool|null $uniqueChatMode                A Boolean value that determines whether the broadcaster requires
     *                                                 users to post only unique messages in the chat room. The default
     *                                                 is false.
     */
    public function __construct(
        public ?bool $emoteMode = null,
        public ?bool $followerMode = null,
        public ?int $followerModeDuration = null,
        public ?bool $nonModeratorChatDelay = null,
        public ?int $nonModeratorChatDelayDuration = null,
        public ?bool $slowMode = null,
        public ?int $slowModeWaitTime = null,
        public ?bool $subscriberMode = null,
        public ?bool $uniqueChatMode = null,
    ) {
        if (null !== $this->followerModeDuration) {
            Assert::greaterThanEq($this->followerModeDuration, 0, 'Follower mode duration can\'t be negative, got %s');
            Assert::lessThanEq(
                $this->followerModeDuration,
                129600,
                'Follower mode duration can\'t exceed 3 months (129600 minutes), got %s',
            );
        }

        if (null !== $this->nonModeratorChatDelayDuration) {
            Assert::inArray(
                $this->nonModeratorChatDelayDuration,
                [2, 4, 6],
                'Invalid non moderator chat delay duration. Allowed values: 2, 4, 6. Got %s',
            );
        }

        if (null !== $this->slowModeWaitTime) {
            Assert::greaterThanEq($this->slowModeWaitTime, 3, 'Slow mode minimum value is 3 seconds, got %s');
            Assert::lessThanEq($this->slowModeWaitTime, 120, 'Slow mode maximum value is 120 seconds, got %s');
        }
    }
}
