<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use DateTimeInterface;
use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\Bits\BitsLeaderboard;
use SimplyStream\TwitchApi\Helix\Models\Bits\Cheermote;
use SimplyStream\TwitchApi\Helix\Models\Bits\ExtensionTransactions;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchDateRangeDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;

class BitsApi extends AbstractApi
{
    protected const BASE_PATH = 'bits';

    /**
     * Gets the Bits leaderboard for the authenticated broadcaster.
     *
     * Authorization
     * Requires a user access token that includes the bits:read scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/bits/leaderboard
     *
     * @param AccessTokenInterface   $accessToken  Requires a user access token that includes the bits:read scope
     * @param int                    $count        The number of results to return. The minimum count is 1 and the
     *                                             maximum is 100. The default is 10.
     * @param string                 $period       The time period over which data is aggregated (uses the PST time
     *                                             zone). Possible values are:
     *
     *                                             - day — A day spans from 00:00:00 on the day specified in started_at
     *                                             and runs through
     *                                             00:00:00 of the next day.
     *                                             - week — A week spans from 00:00:00 on the Monday of the week
     *                                             specified in started_at and runs through 00:00:00 of the next
     *                                             Monday.
     *                                             - month — A month spans from 00:00:00 on the first day of the month
     *                                             specified in started_at and runs through 00:00:00 of the first day
     *                                             of the next month.
     *                                             - year — A year spans from 00:00:00 on the first day of the year
     *                                             specified in started_at and runs through 00:00:00 of the first day
     *                                             of the next year.
     *                                             - all — Default. The lifetime of the broadcaster's channel.
     * @param DateTimeInterface|null $startedAt    The start date, in RFC3339 format, used for determining the
     *                                             aggregation period. Specify this parameter only if you specify the
     *                                             period query parameter. The start date is ignored if period is all.
     *
     *                                             Note that the date is converted to PST before being used, so if you
     *                                             set the start time to 2022-01-01T00:00:00.0Z and period to month,
     *                                             the actual reporting period is December
     *                                             2021, not January 2022. If you want the reporting period to be
     *                                             January 2022, you must set the start time to 2022-01-01T08:00:00.0Z
     *                                             or 2022-01-01T00:00:00.0-08:00.
     *
     *                                             If your start date uses the ‘+’ offset operator (for example,
     *                                             2022-01-01T00:00:00.0+05:00), you must URL encode the start date.
     * @param string|null            $userId       An ID that identifies a user that cheered bits in the channel. If
     *                                             count is greater than
     *                                             1, the response may include users ranked above and below the
     *                                             specified user. To get the leaderboard’s top leaders, don’t specify
     *                                             a user ID.
     *
     * @return TwitchDateRangeDataResponse<BitsLeaderboard[]>
     */
    public function getBitsLeaderboard(
        AccessTokenInterface $accessToken,
        int $count = 10,
        string $period = 'all',
        DateTimeInterface $startedAt = null,
        string $userId = null
    ): TwitchDateRangeDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/leaderboard',
            query: [
                'count' => $count,
                'period' => $period,
                'started_at' => $startedAt?->format(DATE_RFC3339_EXTENDED),
                'user_id' => $userId,
            ],
            type: sprintf('%s<%s[]>', TwitchDateRangeDataResponse::class, BitsLeaderboard::class),
            accessToken: $accessToken
        );
    }

    /**
     * Gets a list of Cheermotes that users can use to cheer Bits in any Bits-enabled channel’s chat room. Cheermotes
     * are animated emotes that viewers can assign Bits to.
     *
     * URL
     * GET https://api.twitch.tv/helix/bits/cheermotes
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * @param AccessTokenInterface $accessToken        Requires an app access token or user access token.
     * @param string|null          $broadcasterId      The ID of the broadcaster whose custom Cheermotes you want to
     *                                                 get. Specify the broadcaster’s ID if you want to include the
     *                                                 broadcaster’s Cheermotes in the response
     *                                                 (not all broadcasters upload Cheermotes). If not specified, the
     *                                                 response contains only global Cheermotes.
     *
     *                                                 If the broadcaster uploaded Cheermotes, the type field in the
     *                                                 response is set to channel_custom.
     *
     * @return TwitchDataResponse<Cheermote[]>
     */
    public function getCheermotes(
        AccessTokenInterface $accessToken,
        string $broadcasterId = null,
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/cheermotes',
            query: [
                'broadcaster_id' => $broadcasterId,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, Cheermote::class),
            accessToken: $accessToken
        );
    }

    /**
     * Gets an extension’s list of transactions. A transaction records the exchange of a currency (for example, Bits)
     * for a digital product.
     *
     * Authorization
     * Requires an app access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/extensions/transactions
     *
     * @param AccessTokenInterface $accessToken      Requires an app access token.
     * @param string               $extensionId      The ID of the extension whose list of transactions you want to get.
     * @param string|null          $id               A transaction ID used to filter the list of transactions. Specify
     *                                               this parameter for each transaction you want to get. For example,
     *                                               id=1234&id=5678. You may specify a maximum of 100 IDs.
     * @param int                  $first            The maximum number of items to return per page in the response.
     *                                               The minimum page size is 1 item per page and the maximum is 100
     *                                               items per page. The default is 20.
     * @param string|null          $after            The cursor used to get the next page of results. The Pagination
     *                                               object in the response contains the cursor’s value.
     *
     * @return TwitchPaginatedDataResponse<ExtensionTransactions[]>
     */
    public function getExtensionTransactions(
        AccessTokenInterface $accessToken,
        string $extensionId,
        string $id = null,
        int $first = 20,
        string $after = null,
    ): TwitchPaginatedDataResponse {
        return $this->sendRequest(
            path: 'extensions/transactions',
            query: [
                'extension_id' => $extensionId,
                'id' => $id,
                'first' => $first,
                'after' => $after,
            ],
            type: sprintf('%s<%s[]>', TwitchPaginatedDataResponse::class, ExtensionTransactions::class),
            accessToken: $accessToken
        );
    }
}
