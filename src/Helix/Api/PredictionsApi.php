<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\Predictions\CreatePredictionRequest;
use SimplyStream\TwitchApi\Helix\Models\Predictions\EndPredictionRequest;
use SimplyStream\TwitchApi\Helix\Models\Predictions\Prediction;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;

class PredictionsApi extends AbstractApi
{
    protected const BASE_PATH = 'predictions';

    /**
     * Gets a list of Channel Points Predictions that the broadcaster created.
     *
     * Authorization
     * Requires a user access token that includes the channel:read:predictions or channel:manage:predictions scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/predictions
     *
     * @param string               $broadcasterId The ID of the broadcaster whose predictions you want to get. This ID
     *                                            must match the user ID associated with the user access token.
     * @param AccessTokenInterface $accessToken   Requires a user access token that includes the
     *                                            channel:read:predictions or channel:manage:predictions scope.
     * @param string|null          $id            The ID of the prediction to get. To specify more than one ID, include
     *                                            this parameter for each prediction you want to get. For example,
     *                                            id=1234&id=5678. You may specify a maximum of 25 IDs. The endpoint
     *                                            ignores duplicate IDs and those not owned by the broadcaster.
     * @param int                  $first         The maximum number of items to return per page in the response. The
     *                                            minimum page size is
     *                                            1 item per page and the maximum is 25 items per page. The default is
     *                                            20.
     * @param string|null          $after         The cursor used to get the next page of results. The Pagination
     *                                            object in the response contains the cursor’s value.
     *
     * @return TwitchPaginatedDataResponse<Prediction[]>
     */
    public function getPredictions(
        string $broadcasterId,
        AccessTokenInterface $accessToken,
        string $id = null,
        int $first = 20,
        string $after = null
    ): TwitchPaginatedDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH,
            query: [
                'broadcaster_id' => $broadcasterId,
                'id' => $id,
                'first' => $first,
                'after' => $after,
            ],
            type: sprintf('%s<%s[]>', TwitchPaginatedDataResponse::class, Prediction::class),
            accessToken: $accessToken
        );
    }

    /**
     * Creates a Channel Points Prediction.
     *
     * With a Channel Points Prediction, the broadcaster poses a question and viewers try to predict the outcome. The
     * prediction runs as soon as it’s created. The broadcaster may run only one prediction at a time.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:predictions scope.
     *
     * URL
     * POST https://api.twitch.tv/helix/predictions
     *
     * @param CreatePredictionRequest $body
     * @param AccessTokenInterface    $accessToken Requires a user access token that includes the
     *                                             channel:manage:predictions scope.
     *
     * @return TwitchDataResponse<Prediction[]>
     */
    public function createPrediction(
        CreatePredictionRequest $body,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH,
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, Prediction::class),
            method: 'POST',
            body: $body,
            accessToken: $accessToken
        );
    }

    /**
     * Locks, resolves, or cancels a Channel Points Prediction.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:predictions scope.
     *
     * URL
     * PATCH https://api.twitch.tv/helix/predictions
     *
     * @param EndPredictionRequest $body
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the
     *                                          channel:manage:predictions scope.
     *
     * @return TwitchDataResponse<Prediction[]>
     */
    public function endPrediction(
        EndPredictionRequest $body,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH,
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, Prediction::class),
            method: 'PATCH',
            body: $body,
            accessToken: $accessToken
        );
    }
}
