<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Search\Request\SearchCategoriesRequest;
use SimplyStream\TwitchApi\Helix\Api\Search\Request\SearchChannelsRequest;
use SimplyStream\TwitchApi\Helix\Api\Search\Response\SearchCategoriesResponse;
use SimplyStream\TwitchApi\Helix\Api\Search\Response\SearchChannelsResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class SearchApi extends AbstractApi
{
    private const string BASE_PATH = 'search';

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
     * @param AccessTokenInterface    $accessToken Requires an app access token or user access token.
     */
    public function searchCategories(
        SearchCategoriesRequest $request,
        AccessTokenInterface $accessToken,
    ): SearchCategoriesResponse {
        $query = array_filter(
            [
                'query' => $request->query,
                'first' => $request->first,
                'after' => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(self::BASE_PATH . '/categories', SearchCategoriesResponse::class, $accessToken, $query);
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
     * @param AccessTokenInterface  $accessToken Requires an app access token or user access token.
     */
    public function searchChannels(
        SearchChannelsRequest $request,
        AccessTokenInterface $accessToken,
    ): SearchChannelsResponse {
        $query = array_filter(
            [
                'query'     => $request->query,
                'live_only' => $request->liveOnly,
                'first'     => $request->first,
                'after'     => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(self::BASE_PATH . '/channels', SearchChannelsResponse::class, $accessToken, $query);
    }
}
