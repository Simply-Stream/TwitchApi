<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Reply
{
    use SerializesModels;

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
        private string $parentMessageId,
        private string $parentMessageBody,
        private string $parentUserId,
        private string $parentUserName,
        private string $parentUserLogin,
        private string $threadMessageId,
        private string $threadUserId,
        private string $threadUserName,
        private string $threadUserLogin,
    ) {
    }

    public function getParentMessageId(): string
    {
        return $this->parentMessageId;
    }

    public function getParentMessageBody(): string
    {
        return $this->parentMessageBody;
    }

    public function getParentUserId(): string
    {
        return $this->parentUserId;
    }

    public function getParentUserName(): string
    {
        return $this->parentUserName;
    }

    public function getParentUserLogin(): string
    {
        return $this->parentUserLogin;
    }

    public function getThreadMessageId(): string
    {
        return $this->threadMessageId;
    }

    public function getThreadUserId(): string
    {
        return $this->threadUserId;
    }

    public function getThreadUserName(): string
    {
        return $this->threadUserName;
    }

    public function getThreadUserLogin(): string
    {
        return $this->threadUserLogin;
    }
}
