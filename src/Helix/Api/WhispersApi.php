<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Whispers\Request\SendWhisperRequest;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class WhispersApi extends AbstractApi
{
    private const string BASE_PATH = 'whispers';

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
     * @param SendWhisperRequest   $request
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the user:manage:whispers
     *                                          scope.
     *
     * @return void
     */
    public function sendWhisper(
        SendWhisperRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->postWithoutResponse(
            self::BASE_PATH,
            $accessToken,
            $this->normalizer->normalize($request->whisper),
            [
                'from_user_id' => $request->fromUserId,
                'to_user_id'   => $request->toUserId,
            ],
        );
    }
}
