<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Streams\Request\CreateStreamMarkerRequest;
use SimplyStream\TwitchApi\Helix\Api\Streams\Request\GetFollowedStreamsRequest;
use SimplyStream\TwitchApi\Helix\Api\Streams\Request\GetStreamKeyRequest;
use SimplyStream\TwitchApi\Helix\Api\Streams\Request\GetStreamMarkersRequest;
use SimplyStream\TwitchApi\Helix\Api\Streams\Request\GetStreamsRequest;
use SimplyStream\TwitchApi\Helix\Api\Streams\Response\CreateStreamMarkerResponse;
use SimplyStream\TwitchApi\Helix\Api\Streams\Response\StreamKeyResponse;
use SimplyStream\TwitchApi\Helix\Api\Streams\Response\StreamMarkersResponse;
use SimplyStream\TwitchApi\Helix\Api\Streams\Response\StreamsResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class StreamsApi extends AbstractApi
{
    private const string BASE_PATH = 'streams';

    /**
     * Gets the channel’s stream key.
     *
     * Authorization
     * Requires a user access token that includes the channel:read:stream_key scope.
     *
     * URL
     * https://api.twitch.tv/helix/streams/key
     *
     * @param GetStreamKeyRequest  $request
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the channel:read:stream_key
     *                                          scope.
     *
     * @return StreamKeyResponse
     */
    public function getStreamKey(
        GetStreamKeyRequest $request,
        AccessTokenInterface $accessToken,
    ): StreamKeyResponse {
        return $this->get(
            self::BASE_PATH . '/key',
            StreamKeyResponse::class,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
            ],
        );
    }

    /**
     * Gets a list of all streams. The list is in descending order by the number of viewers watching the stream.
     * Because viewers come and go during a stream, it’s possible to find duplicate or missing streams in the list as
     * you page through the results.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/streams
     *
     * @param GetStreamsRequest    $request
     * @param AccessTokenInterface $accessToken Requires an app access token or user access token.
     *
     * @return StreamsResponse
     */
    public function getStreams(
        GetStreamsRequest $request,
        AccessTokenInterface $accessToken,
    ): StreamsResponse {
        $query = array_filter(
            [
                'user_id'    => $request->userIds,
                'user_login' => $request->userLogins,
                'game_id'    => $request->gameIds,
                'type'       => $request->type->value,
                'language'   => $request->languages,
                'first'      => $request->first,
                'before'     => $request->before,
                'after'      => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null && $v !== [],
        );

        return $this->get(self::BASE_PATH, StreamsResponse::class, $accessToken, $query);
    }

    /**
     * Gets the list of broadcasters that the user follows and that are streaming live.
     *
     * Authorization
     * Requires a user access token that includes the user:read:follows scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/streams/followed
     *
     * @param GetFollowedStreamsRequest $request
     * @param AccessTokenInterface      $accessToken Requires a user access token that includes the user:read:follows
     *                                               scope.
     *
     * @return StreamsResponse
     */
    public function getFollowedStreams(
        GetFollowedStreamsRequest $request,
        AccessTokenInterface $accessToken,
    ): StreamsResponse {
        $query = array_filter(
            [
                'user_id' => $request->userId,
                'first'   => $request->first,
                'after'   => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(self::BASE_PATH . '/followed', StreamsResponse::class, $accessToken, $query);
    }

    /**
     * Adds a marker to a live stream. A marker is an arbitrary point in a live stream that the broadcaster or editor
     * wants to mark, so they can return to that spot later to create video highlights (see Video Producer, Highlights
     * in the Twitch UX).
     *
     * You may not add markers:
     *
     * - If the stream is not live
     * - If the stream has not enabled video on demand (VOD)
     * - If the stream is a premiere (a live, first-viewing event that combines uploaded videos with live chat)
     * - If the stream is a rerun of a past broadcast, including past premieres.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:broadcast scope.
     *
     * URL
     * POST https://api.twitch.tv/helix/streams/markers
     *
     * @param CreateStreamMarkerRequest $request
     * @param AccessTokenInterface      $accessToken Requires a user access token that includes the
     *                                               channel:manage:broadcast scope.
     *
     * @return CreateStreamMarkerResponse
     */
    public function createStreamMarker(
        CreateStreamMarkerRequest $request,
        AccessTokenInterface $accessToken,
    ): CreateStreamMarkerResponse {
        return $this->post(
            self::BASE_PATH . '/markers',
            CreateStreamMarkerResponse::class,
            $accessToken,
            $this->normalizer->normalize($request->marker),
        );
    }

    /**
     * Gets a list of markers from the user’s most recent stream or from the specified VOD/video. A marker is an
     * arbitrary point in a live stream that the broadcaster or editor marked, so they can return to that spot later to
     * create video highlights (see Video Producer, Highlights in the Twitch UX).
     *
     * Authorization
     * Requires a user access token that includes the user:read:broadcast or channel:manage:broadcast scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/streams/markers
     *
     * @param GetStreamMarkersRequest $request
     * @param AccessTokenInterface    $accessToken Requires a user access token that includes the user:read:broadcast or
     *                                             channel:manage:broadcast scope.
     *
     * @return StreamMarkersResponse
     */
    public function getStreamMarkers(
        GetStreamMarkersRequest $request,
        AccessTokenInterface $accessToken,
    ): StreamMarkersResponse {
        $query = array_filter(
            [
                'user_id'  => $request->userId,
                'video_id' => $request->videoId,
                'first'    => $request->first,
                'before'   => $request->before,
                'after'    => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(self::BASE_PATH . '/markers', StreamMarkersResponse::class, $accessToken, $query);
    }
}
