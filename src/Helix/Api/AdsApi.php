<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\Ads\AdSchedule;
use SimplyStream\TwitchApi\Helix\Models\Ads\Commercial;
use SimplyStream\TwitchApi\Helix\Models\Ads\SnoozeNextAd;
use SimplyStream\TwitchApi\Helix\Models\Ads\StartCommercialRequest;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;

class AdsApi extends AbstractApi
{
    protected const BASE_PATH = 'channels';

    /**
     * Starts a commercial on the specified channel.
     *
     * NOTE: Only partners and affiliates may run commercials and they must be streaming live at the time.
     *
     * NOTE: Only the broadcaster may start a commercial; the broadcaster’s editors and moderators may not start
     * commercials on behalf of the broadcaster.
     *
     * Authentication
     * Requires a user access token that includes the channel:edit:commercial scope.
     *
     * URL
     * POST https://api.twitch.tv/helix/channels/commercial
     *
     * @param StartCommercialRequest $body
     * @param AccessTokenInterface   $accessToken Requires a user access token that includes the
     *                                            channel:edit:commercial scope.
     *
     * @return TwitchDataResponse<Commercial[]>
     */
    public function startCommercial(
        StartCommercialRequest $body,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/commercial',
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, Commercial::class),
            method: 'POST',
            body: $body,
            accessToken: $accessToken
        );
    }

    /**
     * This endpoint returns ad schedule related information, including snooze, when the last ad was run, when the next
     * ad is scheduled, and if the channel is currently in pre-roll free time. Note that a new ad cannot be run until 8
     * minutes after running a previous ad.
     *
     * Authorization
     * Requires a user access token that includes the channel:read:ads scope. The user_id in the user access token must
     * match the broadcaster_id.
     *
     * URL
     * GET https://api.twitch.tv/helix/channels/ads
     *
     * @param string               $broadcasterId Provided broadcaster_id must match the user_id in the auth token.
     * @param AccessTokenInterface $accessToken   Requires a user access token that includes the channel:read:ads
     *                                            scope. The user_id in the user access token must match the
     *                                            broadcaster_id.
     *
     * @return TwitchDataResponse<AdSchedule[]>
     */
    public function getAdSchedule(
        string $broadcasterId,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/ads',
            query: [
                'broadcaster_id' => $broadcasterId,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, AdSchedule::class),
            accessToken: $accessToken
        );
    }

    /**
     * If available, pushes back the timestamp of the upcoming automatic mid-roll ad by 5 minutes. This endpoint
     * duplicates the snooze functionality in the creator dashboard’s Ads Manager.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:ads scope. The user_id in the user access token
     * must match the broadcaster_id.
     *
     * URL
     * POST https://api.twitch.tv/helix/channels/ads/schedule/snooze
     *
     * @param string               $broadcasterId Provided broadcaster_id must match the user_id in the auth token.
     * @param AccessTokenInterface $accessToken   Requires a user access token that includes the channel:manage:ads
     *                                            scope. The user_id in the user access token must match the
     *                                            broadcaster_id.
     *
     * @return TwitchDataResponse<SnoozeNextAd[]>
     */
    public function snoozeNextAd(
        string $broadcasterId,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/ads/schedule/snooze',
            query: [
                'broadcaster_id' => $broadcasterId,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, SnoozeNextAd::class),
            accessToken: $accessToken
        );
    }
}
