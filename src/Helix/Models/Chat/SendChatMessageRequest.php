<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Chat;

use SimplyStream\TwitchApi\Helix\Models\AbstractModel;
use Webmozart\Assert\Assert;

final readonly class SendChatMessageRequest extends AbstractModel
{
    /**
     * @param string      $broadcasterId        The ID of the broadcaster whose chat room the message will be sent to.
     * @param string      $senderId             The ID of the user sending the message. This ID must match the user ID
     *                                          in the user access token.
     * @param string      $message              The message to send. The message is limited to a maximum of 500
     *                                          characters. Chat messages can also include emoticons. To include
     *                                          emoticons, use the name of the emote. The names are case sensitive.
     *                                          Don’t include colons around the name (e.g., :bleedPurple:). If Twitch
     *                                          recognizes the name, Twitch converts the name to the emote before
     *                                          writing the chat message to the chat room
     * @param string|null $replyParentMessageId The ID of the chat message being replied to.
     */
    public function __construct(
        private string $broadcasterId,
        private string $senderId,
        private string $message,
        private ?string $replyParentMessageId = null,
    ) {
        Assert::maxLength($message, 500, 'The message is limited to a maximum of %2$s characters. Got "%s"');
    }

    public function getBroadcasterId(): string
    {
        return $this->broadcasterId;
    }

    public function getSenderId(): string
    {
        return $this->senderId;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getReplyParentMessageId(): string
    {
        return $this->replyParentMessageId;
    }
}
