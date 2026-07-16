<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Ads\Request\GetAdScheduleRequest;
use SimplyStream\TwitchApi\Helix\Api\Ads\Request\SnoozeNextAdRequest;
use SimplyStream\TwitchApi\Helix\Api\Ads\Request\StartCommercialRequest;
use SimplyStream\TwitchApi\Helix\Api\Ads\Response\AdScheduleResponse;
use SimplyStream\TwitchApi\Helix\Api\Ads\Response\SnoozeNextAdResponse;
use SimplyStream\TwitchApi\Helix\Api\Ads\Response\StartCommercialResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class AdsApi extends AbstractApi
{
    private const string BASE_PATH = 'channels';

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
     * @param AccessTokenInterface   $accessToken Requires a user access token that includes the
     *                                            channel:edit:commercial scope.
     */
    public function startCommercial(
        StartCommercialRequest $request,
        AccessTokenInterface $accessToken,
    ): StartCommercialResponse {
        return $this->post(
            self::BASE_PATH . '/commercial',
            StartCommercialResponse::class,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
                'length'         => $request->length,
            ],
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
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the channel:read:ads
     *                                          scope. The user_id in the user access token must match the
     *                                          broadcaster_id.
     */
    public function getAdSchedule(
        GetAdScheduleRequest $request,
        AccessTokenInterface $accessToken,
    ): AdScheduleResponse {
        return $this->get(
            self::BASE_PATH . '/ads',
            AdScheduleResponse::class,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
            ],
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
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the channel:manage:ads
     *                                          scope. The user_id in the user access token must match the
     *                                          broadcaster_id.
     */
    public function snoozeNextAd(
        SnoozeNextAdRequest $request,
        AccessTokenInterface $accessToken,
    ): SnoozeNextAdResponse {
        return $this->post(
            self::BASE_PATH . '/ads/schedule/snooze',
            SnoozeNextAdResponse::class,
            $accessToken,
            query: [
                'broadcaster_id' => $request->broadcasterId,
            ],
        );
    }
}
