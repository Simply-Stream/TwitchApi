<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChatMessage;

final readonly class Reply
{
    /**
     * @param string $parentMessageId   An ID that uniquely identifies the parent message that this message is replying
     *                                  to.
     * @param string $parentMessageBody The message body of the parent message.
     * @param string $parentUserId      User ID of the sender of the parent message.
     * @param string $parentUserName    User name of the sender of the parent message.
     * @param string $parentUserLogin   User login of the sender of the parent message.
     * @param string $threadMessageId   An ID that identifies the parent message of the reply thread.
     * @param string $threadUserId      User ID of the sender of the thread’s parent message.
     * @param string $threadUserName    User name of the sender of the thread’s parent message.
     * @param string $threadUserLogin   User login of the sender of the thread’s parent message.
     */
    public function __construct(
        public string $parentMessageId,
        public string $parentMessageBody,
        public string $parentUserId,
        public string $parentUserName,
        public string $parentUserLogin,
        public string $threadMessageId,
        public string $threadUserId,
        public string $threadUserName,
        public string $threadUserLogin,
    ) {
    }
}
