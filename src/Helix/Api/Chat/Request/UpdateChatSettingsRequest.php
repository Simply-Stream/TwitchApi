<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Chat\Request;

use SimplyStream\TwitchApi\Helix\Models\Chat\UpdateChatSettings;

final readonly class UpdateChatSettingsRequest
{
    /**
     * @param string             $broadcasterId The ID of the broadcaster whose chat settings you want to update.
     * @param string             $moderatorId   The ID of a user that has permission to moderate the broadcaster’s chat
     *                                          room, or the broadcaster’s ID if they’re making the update. This ID must
     *                                          match the user ID in the user access token.
     * @param UpdateChatSettings $settings      The chat settings to update.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
        public UpdateChatSettings $settings,
    ) {
    }
}
