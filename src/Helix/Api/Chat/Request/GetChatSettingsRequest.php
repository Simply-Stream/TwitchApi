<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Chat\Request;

final readonly class GetChatSettingsRequest
{
    /**
     * @param string      $broadcasterId The ID of the broadcaster whose chat settings you want to get.
     * @param string|null $moderatorId   The ID of a user that has permission to moderate the broadcaster’s chat room,
     *                                   or the broadcaster’s ID if they’re getting the settings.
     *
     *                                   This field is required only if you want to include the non_moderator_chat_delay
     *                                   and non_moderator_chat_delay_duration settings in the response.
     *
     *                                   If you specify this field, this ID must match the user ID in the user access
     *                                   token.
     */
    public function __construct(
        public string $broadcasterId,
        public ?string $moderatorId = null,
    ) {
    }
}
