<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Whispers\Request;

use SimplyStream\TwitchApi\Helix\Models\Whispers\SendWhisper;

final readonly class SendWhisperRequest
{
    /**
     * @param string      $fromUserId The ID of the user sending the whisper. This user must have a verified phone
     *                               number. This ID must match the user ID in the user access token.
     * @param string      $toUserId   The ID of the user to receive the whisper.
     * @param SendWhisper $whisper    The whisper to send (message body).
     */
    public function __construct(
        public string $fromUserId,
        public string $toUserId,
        public SendWhisper $whisper,
    ) {
    }
}
