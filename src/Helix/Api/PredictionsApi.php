<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Predictions\Request\CreatePredictionRequest;
use SimplyStream\TwitchApi\Helix\Api\Predictions\Request\EndPredictionRequest;
use SimplyStream\TwitchApi\Helix\Api\Predictions\Request\GetPredictionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Predictions\Response\PredictionResponse;
use SimplyStream\TwitchApi\Helix\Api\Predictions\Response\PredictionsResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class PredictionsApi extends AbstractApi
{
    private const string BASE_PATH = 'predictions';

    /**
     * Gets a list of Channel Points Predictions that the broadcaster created.
     *
     * Authorization
     * Requires a user access token that includes the channel:read:predictions or channel:manage:predictions scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/predictions
     *
     * @param AccessTokenInterface  $accessToken Requires a user access token that includes the channel:read:predictions
     *                                           or channel:manage:predictions scope.
     */
    public function getPredictions(
        GetPredictionsRequest $request,
        AccessTokenInterface $accessToken,
    ): PredictionsResponse {
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
                'id'             => $request->ids,
                'first'          => $request->first,
                'after'          => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null && $v !== [],
        );

        return $this->get(self::BASE_PATH, PredictionsResponse::class, $accessToken, $query);
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
     * @param AccessTokenInterface    $accessToken Requires a user access token that includes the
     *                                             channel:manage:predictions scope.
     */
    public function createPrediction(
        CreatePredictionRequest $request,
        AccessTokenInterface $accessToken,
    ): PredictionResponse {
        return $this->post(
            self::BASE_PATH,
            PredictionResponse::class,
            $accessToken,
            $this->normalizer->normalize($request->prediction),
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
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the channel:manage:predictions
     *                                          scope.
     */
    public function endPrediction(
        EndPredictionRequest $request,
        AccessTokenInterface $accessToken,
    ): PredictionResponse {
        return $this->patch(
            self::BASE_PATH,
            PredictionResponse::class,
            $accessToken,
            $this->normalizer->normalize($request->prediction),
        );
    }
}
