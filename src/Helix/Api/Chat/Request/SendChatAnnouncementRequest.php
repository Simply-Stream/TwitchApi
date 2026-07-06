<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Chat\Request;

use SimplyStream\TwitchApi\Helix\Models\Chat\SendChatAnnouncement;

final readonly class SendChatAnnouncementRequest
{
    /**
     * @param string                $broadcasterId The ID of the broadcaster that owns the chat room to send the
     *                                            announcement to.
     * @param string                $moderatorId   The ID of a user who has permission to moderate the broadcaster’s
     *                                            chat room, or the broadcaster’s ID if they’re sending the
     *                                            announcement. This ID must match the user ID in the user access token.
     * @param SendChatAnnouncement  $announcement  The announcement to send.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
        public SendChatAnnouncement $announcement,
    ) {
    }
}
