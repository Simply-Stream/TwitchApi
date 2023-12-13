<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\HypeTrain\HypeTrainEvent;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;

class HypeTrainApi extends AbstractApi
{
    protected const BASE_PATH = 'hypetrain';

    /**
     * Gets information about the broadcaster’s current or most recent Hype Train event.
     *
     * Instead of polling for events, consider subscribing to Hype Train events (Begin, Progress, End).
     *
     * Authorization
     * Requires a user access token that includes the channel:read:hype_train scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/hypetrain/events
     *
     * @param AccessTokenInterface $accessToken        Requires a user access token that includes the
     *                                                 channel:read:hype_train scope.
     * @param string               $broadcasterId      The ID of the broadcaster that’s running the Hype Train. This ID
     *                                                 must match the User ID in the user access token.
     * @param int                  $first              The maximum number of items to return per page in the response.
     *                                                 The minimum page size is 1 item per page and the maximum is 100
     *                                                 items per page. The default is 1.
     * @param string|null          $after              The cursor used to get the next page of results. The Pagination
     *                                                 object in the response contains the cursor’s value.
     *
     * @return TwitchPaginatedDataResponse<HypeTrainEvent[]>
     */
    public function getHypeTrainEvents(
        string $broadcasterId,
        AccessTokenInterface $accessToken,
        int $first = 1,
        string $after = null,
    ): TwitchPaginatedDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/events',
            query: [
                'broadcaster_id' => $broadcasterId,
                'first' => $first,
                'after' => $after,
            ],
            type: sprintf('%s<%s[]>', TwitchPaginatedDataResponse::class, HypeTrainEvent::class),
            accessToken: $accessToken
        );
    }
}
