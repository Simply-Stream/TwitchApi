<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\Search\Category;
use SimplyStream\TwitchApi\Helix\Models\Search\Channel;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;

class SearchApi extends AbstractApi
{
    protected const BASE_PATH = 'search';

    /**
     * Gets the games or categories that match the specified query.
     *
     * To match, the category’s name must contain all parts of the query string. For example, if the query string is
     * 42, the response includes any category name that contains 42 in the title. If the query string is a phrase like
     * love computer, the response includes any category name that contains the words love and computer anywhere in the
     * name. The comparison is case insensitive.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/search/categories
     *
     * @param string               $query            The URI-encoded search string. For example, encode #archery as
     *                                               %23archery and search strings like angel of death as
     *                                               angel%20of%20death.
     * @param AccessTokenInterface $accessToken      Requires an app access token or user access token.
     * @param int                  $first            The maximum number of items to return per page in the response.
     *                                               The
     *                                               minimum page size is 1 item per page and the maximum is 100 items
     *                                               per page. The default is 20.
     * @param string|null          $after            The cursor used to get the next page of results. The Pagination
     *                                               object in the response contains the cursor’s value.
     *
     * @return TwitchPaginatedDataResponse<Category[]>
     */
    public function searchCategories(
        string $query,
        AccessTokenInterface $accessToken,
        int $first = 20,
        string $after = null,
    ): TwitchPaginatedDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/categories',
            query: [
                'query' => $query,
                'first' => $first,
                'after' => $after,
            ],
            type: sprintf('%s<%s[]>', TwitchPaginatedDataResponse::class, Category::class),
            accessToken: $accessToken
        );
    }

    /**
     * Gets the channels that match the specified query and have streamed content within the past 6 months.
     *
     * The fields that the API uses for comparison depends on the value that the live_only query parameter is set to.
     * If live_only is false, the API matches on the broadcaster’s login name. However, if live_only is true, the API
     * matches on the broadcaster’s name and category name.
     *
     * To match, the beginning of the broadcaster’s name or category must match the query string. The comparison is
     * case insensitive. If the query string is angel_of_death, it matches all names that begin with angel_of_death.
     * However, if the query string is a phrase like angel of death, it matches to names starting with angelofdeath or
     * names starting with angel_of_death.
     *
     * By default, the results include both live and offline channels. To get only live channels set the live_only
     * query parameter to true.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/search/channels
     *
     * @param string               $query         The URI-encoded search string. For example, encode search strings
     *                                            like angel of death as angel%20of%20death.
     * @param AccessTokenInterface $accessToken   Requires an app access token or user access token.
     * @param bool                 $liveOnly      A Boolean value that determines whether the response includes only
     *                                            channels that are currently streaming live. Set to true to get only
     *                                            channels that are streaming live; otherwise, false to get live and
     *                                            offline channels. The default is false.
     * @param int                  $first         The maximum number of items to return per page in the response. The
     *                                            minimum page size is
     *                                            1 item per page and the maximum is 100 items per page. The default is
     *                                            20.
     * @param string|null          $after         The cursor used to get the next page of results. The Pagination
     *                                            object in the response contains the cursor’s value.
     *
     * @return TwitchPaginatedDataResponse<Channel[]>
     */
    public function searchChannels(
        string $query,
        AccessTokenInterface $accessToken,
        bool $liveOnly = false,
        int $first = 20,
        string $after = null,
    ): TwitchPaginatedDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/channels',
            query: [
                'query' => $query,
                'live_only' => $liveOnly,
                'first' => $first,
                'after' => $after,
            ],
            type: sprintf('%s<%s[]>', TwitchPaginatedDataResponse::class, Channel::class),
            accessToken: $accessToken
        );
    }
}
