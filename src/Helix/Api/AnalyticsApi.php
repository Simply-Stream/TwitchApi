<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Analytics\Request\GetExtensionAnalyticsRequest;
use SimplyStream\TwitchApi\Helix\Api\Analytics\Request\GetGameAnalyticsRequest;
use SimplyStream\TwitchApi\Helix\Api\Analytics\Response\ExtensionAnalyticsResponse;
use SimplyStream\TwitchApi\Helix\Api\Analytics\Response\GameAnalyticsResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class AnalyticsApi extends AbstractApi
{
    private const string BASE_PATH = 'analytics';

    /**
     * Gets an analytics report for one or more extensions. The response contains the URLs used to download the reports
     * (CSV files).
     *
     * Authorization
     * Requires a user access token that includes the analytics:read:extensions scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/analytics/extensions
     *
     * @see https://dev.twitch.tv/docs/insights Insights & Analytics
     *
     * @param AccessTokenInterface         $accessToken Requires a user access token that includes the
     *                                                  analytics:read:extensions scope.
     */
    public function getExtensionAnalytics(
        GetExtensionAnalyticsRequest $request,
        AccessTokenInterface $accessToken,
    ): ExtensionAnalyticsResponse {
        $query = array_filter(
            [
                'extension_id' => $request->extensionId,
                'type'         => $request->type,
                'started_at'   => $request->startedAt?->format(DATE_RFC3339_EXTENDED),
                'ended_at'     => $request->endedAt?->format(DATE_RFC3339_EXTENDED),
                'first'        => $request->first,
                'after'        => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(
            self::BASE_PATH . '/extensions',
            ExtensionAnalyticsResponse::class,
            $accessToken,
            $query,
        );
    }

    /**
     * Gets an analytics report for one or more games. The response contains the URLs used to download the reports (CSV
     * files).
     *
     * Authorization
     * Requires a user access token that includes the analytics:read:games scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/analytics/games
     *
     * @see https://dev.twitch.tv/docs/insights Insights & Analytics
     *
     * @param AccessTokenInterface    $accessToken Requires a user access token that includes the analytics:read:games
     *                                             scope.
     */
    public function getGameAnalytics(
        GetGameAnalyticsRequest $request,
        AccessTokenInterface $accessToken,
    ): GameAnalyticsResponse {
        $query = array_filter(
            [
                'game_id'    => $request->gameId,
                'type'       => $request->type,
                'started_at' => $request->startedAt?->format(DATE_RFC3339),
                'ended_at'   => $request->endedAt?->format(DATE_RFC3339),
                'first'      => $request->first,
                'after'      => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(
            self::BASE_PATH . '/games',
            GameAnalyticsResponse::class,
            $accessToken,
            $query,
        );
    }
}
