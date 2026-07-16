<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Raids\Request\CancelRaidRequest;
use SimplyStream\TwitchApi\Helix\Api\Raids\Request\StartRaidRequest;
use SimplyStream\TwitchApi\Helix\Api\Raids\Response\RaidResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class RaidsApi extends AbstractApi
{
    private const string BASE_PATH = 'raids';

    /**
     * Raid another channel by sending the broadcaster’s viewers to the targeted channel.
     *
     * When you call the API from a chat bot or extension, the Twitch UX pops up a window at the top of the chat room
     * that identifies the number of viewers in the raid. The raid occurs when the broadcaster clicks Raid Now or after
     * the 90-second countdown expires.
     *
     * To determine whether the raid successfully occurred, you must subscribe to the Channel Raid event. For more
     * information, see Get notified when a raid begins.
     *
     * To cancel a pending raid, use the Cancel a raid endpoint.
     *
     * Rate Limit: The limit is 10 requests within a 10-minute window.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:raids scope.
     *
     * URL
     * POST https://api.twitch.tv/helix/raids
     *
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the channel:manage:raids
     *                                          scope.
     */
    public function startRaid(
        StartRaidRequest $request,
        AccessTokenInterface $accessToken,
    ): RaidResponse {
        return $this->post(
            self::BASE_PATH,
            RaidResponse::class,
            $accessToken,
            query: [
                'from_broadcaster_id' => $request->fromBroadcasterId,
                'to_broadcaster_id'   => $request->toBroadcasterId,
            ],
        );
    }

    /**
     * Cancel a pending raid.
     *
     * You can cancel a raid at any point up until the broadcaster clicks Raid Now in the Twitch UX or the 90-second
     * countdown expires.
     *
     * Rate Limit: The limit is 10 requests within a 10-minute window.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:raids scope.
     *
     * URL
     * DELETE https://api.twitch.tv/helix/raids
     *
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the channel:manage:raids
     *                                          scope.
     */
    public function cancelRaid(
        CancelRaidRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->delete(
            self::BASE_PATH,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
            ],
        );
    }
}
