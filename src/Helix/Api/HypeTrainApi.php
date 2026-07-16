<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\HypeTrain\Request\GetHypeTrainEventsRequest;
use SimplyStream\TwitchApi\Helix\Api\HypeTrain\Response\HypeTrainEventsResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class HypeTrainApi extends AbstractApi
{
    private const string BASE_PATH = 'hypetrain';

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
     * @param AccessTokenInterface      $accessToken Requires a user access token that includes the
     *                                               channel:read:hype_train scope.
     */
    public function getHypeTrainEvents(
        GetHypeTrainEventsRequest $request,
        AccessTokenInterface $accessToken,
    ): HypeTrainEventsResponse {
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
                'first'          => $request->first,
                'after'          => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(
            self::BASE_PATH . '/events',
            HypeTrainEventsResponse::class,
            $accessToken,
            $query,
        );
    }
}
