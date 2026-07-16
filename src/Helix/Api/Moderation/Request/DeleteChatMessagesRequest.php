<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Request;

final readonly class DeleteChatMessagesRequest
{
    /**
     * @param string      $broadcasterId The ID of the broadcaster that owns the chat room to remove messages from.
     * @param string      $moderatorId   The ID of the broadcaster or a user that has permission to moderate the
     *                                  broadcaster’s chat room. This ID must match the user ID in the user access
     *                                  token.
     * @param string|null $messageId     The ID of the message to remove. The id tag in the PRIVMSG tag contains the
     *                                  message’s ID. Restrictions:
     *                                  - The message must have been created within the last 6 hours.
     *                                  - The message must not belong to the broadcaster.
     *                                  - The message must not belong to another moderator.
     *                                  If not specified, the request removes all messages in the broadcaster’s chat
     *                                  room.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
        public ?string $messageId = null,
    ) {
    }
}
