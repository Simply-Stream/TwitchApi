<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Polls\Request\CreatePollRequest;
use SimplyStream\TwitchApi\Helix\Api\Polls\Request\EndPollRequest;
use SimplyStream\TwitchApi\Helix\Api\Polls\Request\GetPollsRequest;
use SimplyStream\TwitchApi\Helix\Api\Polls\Response\PollResponse;
use SimplyStream\TwitchApi\Helix\Api\Polls\Response\PollsResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class PollsApi extends AbstractApi
{
    private const string BASE_PATH = 'polls';

    /**
     * Gets a list of polls that the broadcaster created.
     *
     * Polls are available for 90 days after they’re created.
     *
     * Authorization
     * Requires a user access token that includes the channel:read:polls or channel:manage:polls scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/polls
     *
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the channel:read:polls or
     *                                          channel:manage:polls scope.
     */
    public function getPolls(
        GetPollsRequest $request,
        AccessTokenInterface $accessToken,
    ): PollsResponse {
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
                'id'             => $request->ids,
                'first'          => $request->first,
                'after'          => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null && $v !== [],
        );

        return $this->get(self::BASE_PATH, PollsResponse::class, $accessToken, $query);
    }

    /**
     * Creates a poll that viewers in the broadcaster’s channel can vote on.
     *
     * The poll begins as soon as it’s created. You may run only one poll at a time.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:polls scope.
     *
     * URL
     * POST https://api.twitch.tv/helix/polls
     *
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the channel:manage:polls
     *                                          scope.
     */
    public function createPoll(
        CreatePollRequest $request,
        AccessTokenInterface $accessToken,
    ): PollResponse {
        return $this->post(
            self::BASE_PATH,
            PollResponse::class,
            $accessToken,
            $this->normalizer->normalize($request->poll),
        );
    }

    /**
     * Ends an active poll. You have the option to end it or end it and archive it.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:polls scope.
     *
     * URL
     * PATCH https://api.twitch.tv/helix/polls
     *
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the channel:manage:polls
     *                                          scope.
     */
    public function endPoll(
        EndPollRequest $request,
        AccessTokenInterface $accessToken,
    ): PollResponse {
        return $this->patch(
            self::BASE_PATH,
            PollResponse::class,
            $accessToken,
            $this->normalizer->normalize($request->poll),
        );
    }
}
