<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\Whispers\SendWhisperRequest;

class WhispersApi extends AbstractApi
{
    protected const BASE_PATH = 'whispers';

    /**
     * Sends a whisper message to the specified user.
     *
     * NOTE: The user sending the whisper must have a verified phone number (see the Phone Number setting in your
     * Security and Privacy settings).
     *
     * NOTE: The API may silently drop whispers that it suspects of violating Twitch policies. (The API does not
     * indicate that it dropped the whisper; it returns a 204 status code as if it succeeded.)
     *
     * Rate Limits: You may whisper to a maximum of 40 unique recipients per day. Within the per day limit, you may
     * whisper a maximum of 3 whispers per second and a maximum of 100 whispers per minute.
     *
     * Authorization
     * Requires a user access token that includes the user:manage:whispers scope.
     *
     * URL
     * POST https://api.twitch.tv/helix/whispers
     *
     * @param string               $fromUserId  The ID of the user sending the whisper. This user must have a verified
     *                                          phone number. This ID must match the user ID in the user access token.
     * @param string               $toUserId    The ID of the user to receive the whisper.
     * @param SendWhisperRequest   $body
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the user:manage:whispers
     *                                          scope.
     *
     * @return void
     */
    public function sendWhisper(
        string $fromUserId,
        string $toUserId,
        SendWhisperRequest $body,
        AccessTokenInterface $accessToken
    ): void {
        $this->sendRequest(
            path: self::BASE_PATH,
            query: [
                'from_user_id' => $fromUserId,
                'to_user_id' => $toUserId,
            ],
            method: 'POST',
            body: $body,
            accessToken: $accessToken
        );
    }
}
