<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Clips\Request\CreateClipRequest;
use SimplyStream\TwitchApi\Helix\Api\Clips\Request\GetClipsRequest;
use SimplyStream\TwitchApi\Helix\Api\Clips\Response\ClipsResponse;
use SimplyStream\TwitchApi\Helix\Api\Clips\Response\CreateClipResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class ClipsApi extends AbstractApi
{
    private const string BASE_PATH = 'clips';

    /**
     * Creates a clip from the broadcaster’s stream.
     *
     * This API captures up to 90 seconds of the broadcaster’s stream. The 90 seconds spans the point in the stream
     * from when you called the API. For example, if you call the API at the 4:00 minute mark, the API captures from
     * approximately the 3:35 mark to approximately the 4:05 minute mark. Twitch tries its best to capture 90 seconds
     * of the stream, but the actual length may be less. This may occur if you begin capturing the clip near the
     * beginning or end of the stream.
     *
     * By default, Twitch publishes up to the last 30 seconds of the 90 seconds window and provides a default title for
     * the clip. To specify the title and the portion of the 90 seconds window that’s used for the clip, use the URL in
     * the response’s edit_url field. You can specify a clip that’s from 5 seconds to 60 seconds in length. The URL is
     * valid for up to 24 hours or until the clip is published, whichever comes first.
     *
     * Creating a clip is an asynchronous process that can take a short amount of time to complete. To determine
     * whether the clip was successfully created, call Get Clips using the clip ID that this request returned. If Get
     * Clips returns the clip, the clip was successfully created. If after 15 seconds Get Clips hasn’t returned the
     * clip, assume it failed.
     *
     * Authorization
     * Requires a user access token that includes the clips:edit scope.
     *
     * URL
     * POST https://api.twitch.tv/helix/clips
     *
     * @param CreateClipRequest    $request
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the clips:edit scope.
     *
     * @return CreateClipResponse
     */
    public function createClip(
        CreateClipRequest $request,
        AccessTokenInterface $accessToken,
    ): CreateClipResponse {
        return $this->post(
            self::BASE_PATH,
            CreateClipResponse::class,
            $accessToken,
            query: [
                'broadcaster_id' => $request->broadcasterId,
                'has_delay'      => $request->hasDelay,
            ],
        );
    }

    /**
     * Gets one or more video clips that were captured from streams. For information about clips, see How to use clips.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/clips
     *
     * @param GetClipsRequest      $request
     * @param AccessTokenInterface $accessToken Requires an app access token or user access token.
     *
     * @return ClipsResponse
     */
    public function getClips(
        GetClipsRequest $request,
        AccessTokenInterface $accessToken,
    ): ClipsResponse {
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
                'game_id'        => $request->gameId,
                'id'             => $request->ids,
                'started_at'     => $request->startedAt?->format(DATE_RFC3339),
                'ended_at'       => $request->endedAt?->format(DATE_RFC3339),
                'after'          => $request->after,
                'before'         => $request->before,
                'first'          => $request->first,
                'is_featured'    => $request->isFeatured,
            ],
            static fn (mixed $v): bool => $v !== null && $v !== [],
        );

        return $this->get(self::BASE_PATH, ClipsResponse::class, $accessToken, $query);
    }
}
