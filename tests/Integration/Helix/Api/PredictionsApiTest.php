<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Predictions\Request\CreatePredictionRequest;
use SimplyStream\TwitchApi\Helix\Api\Predictions\Request\EndPredictionRequest;
use SimplyStream\TwitchApi\Helix\Api\Predictions\Request\GetPredictionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Predictions\Response\PredictionResponse;
use SimplyStream\TwitchApi\Helix\Api\Predictions\Response\PredictionsResponse;
use SimplyStream\TwitchApi\Helix\Api\PredictionsApi;
use SimplyStream\TwitchApi\Helix\Models\Predictions\CreatePrediction;
use SimplyStream\TwitchApi\Helix\Models\Predictions\EndPrediction;
use SimplyStream\TwitchApi\Helix\Models\Predictions\Outcome;
use SimplyStream\TwitchApi\Helix\Models\Predictions\Prediction;
use SimplyStream\TwitchApi\Helix\Models\Predictions\Predictor;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsPredictionsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(PredictionsApi::class)]
final class PredictionsApiTest extends TestCase
{
    use BuildsPredictionsApi;

    /** @return array<string, mixed> */
    private function activePredictionPayload(): array
    {
        return [
            'id'                => 'd6676d5c-c86e-44d2-bfc4-100fb48f0656',
            'broadcaster_id'    => '55696719',
            'broadcaster_name'  => 'TwitchDev',
            'broadcaster_login' => 'twitchdev',
            'title'             => 'Will there be any leaks today?',
            'winning_outcome_id' => null,
            'outcomes'          => [
                [
                    'id'             => '021e9234-5893-49b4-982e-cfe9a0aaddd9',
                    'title'          => 'Yes',
                    'users'          => 0,
                    'channel_points' => 0,
                    'top_predictors' => null,
                    'color'          => 'BLUE',
                ],
                [
                    'id'             => 'ded84c26-13cb-4b09-8b3a-a2b8c41bc4a4',
                    'title'          => 'No',
                    'users'          => 0,
                    'channel_points' => 0,
                    'top_predictors' => null,
                    'color'          => 'PINK',
                ],
            ],
            'prediction_window' => 600,
            'status'            => 'ACTIVE',
            'created_at'        => '2021-04-28T16:03:06.320848689Z',
            'ended_at'          => null,
            'locked_at'         => null,
        ];
    }

    #[Test]
    public function get_predictions_denormalizes_an_active_prediction(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data'       => [$this->activePredictionPayload()],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getPredictions(
            new GetPredictionsRequest(broadcasterId: '55696719'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/predictions', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=55696719&first=20', $request->getUri()->getQuery());

        $this->assertInstanceOf(PredictionsResponse::class, $response);
        $this->assertSame('cursor-1', $response->pagination?->cursor);

        $prediction = $response->data[0];
        $this->assertInstanceOf(Prediction::class, $prediction);
        $this->assertSame('Will there be any leaks today?', $prediction->title);
        $this->assertSame('ACTIVE', $prediction->status);
        $this->assertSame(600, $prediction->predictionWindow);
        $this->assertInstanceOf(DateTimeInterface::class, $prediction->createdAt);

        // An active prediction has no winner, no end, no lock.
        $this->assertNull($prediction->winningOutcomeId);
        $this->assertNull($prediction->endedAt);
        $this->assertNull($prediction->lockedAt);

        $this->assertCount(2, $prediction->outcomes);
        $outcome = $prediction->outcomes[0];
        $this->assertInstanceOf(Outcome::class, $outcome);
        $this->assertSame('Yes', $outcome->title);
        $this->assertSame('BLUE', $outcome->color);
        $this->assertSame(0, $outcome->users);
        $this->assertNull($outcome->topPredictors);

        $this->assertSame('PINK', $prediction->outcomes[1]->color);
    }

    #[Test]
    public function get_predictions_denormalizes_the_top_predictors(): void
    {
        $payload = $this->activePredictionPayload();
        $payload['status'] = 'RESOLVED';
        $payload['winning_outcome_id'] = '021e9234-5893-49b4-982e-cfe9a0aaddd9';
        $payload['ended_at'] = '2021-04-28T16:08:06.320848689Z';
        $payload['locked_at'] = '2021-04-28T16:07:06.320848689Z';
        $payload['outcomes'][0]['users'] = 2;
        $payload['outcomes'][0]['channel_points'] = 1800;
        $payload['outcomes'][0]['top_predictors'] = [[
            'user_id'             => '134247454',
            'user_name'           => 'Chief_Bard',
            'user_login'          => 'chief_bard',
            'channel_points_used' => 1000,
            'channel_points_won'  => 3000,
        ]];

        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => [$payload]], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getPredictions(
            new GetPredictionsRequest(broadcasterId: '55696719'),
            new StaticAccessToken(),
        );

        $prediction = $response->data[0];
        $this->assertSame('RESOLVED', $prediction->status);
        $this->assertSame('021e9234-5893-49b4-982e-cfe9a0aaddd9', $prediction->winningOutcomeId);
        $this->assertInstanceOf(DateTimeInterface::class, $prediction->endedAt);
        $this->assertInstanceOf(DateTimeInterface::class, $prediction->lockedAt);

        $winner = $prediction->outcomes[0];
        $this->assertSame(1800, $winner->channelPoints);
        $this->assertCount(1, $winner->topPredictors);

        $predictor = $winner->topPredictors[0];
        $this->assertInstanceOf(Predictor::class, $predictor);
        $this->assertSame('Chief_Bard', $predictor->userName);
        $this->assertSame(1000, $predictor->channelPointsUsed);
        $this->assertSame(3000, $predictor->channelPointsWon);

        // The losing outcome keeps its nulls.
        $this->assertNull($prediction->outcomes[1]->topPredictors);
    }

    #[Test]
    public function get_predictions_repeats_the_ids(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getPredictions(
            new GetPredictionsRequest(broadcasterId: '55696719', ids: ['pr-1', 'pr-2'], first: 5, after: 'cursor-1'),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'broadcaster_id=55696719&id=pr-1&id=pr-2&first=5&after=cursor-1',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function create_prediction_sends_the_outcomes_as_plain_arrays(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [$this->activePredictionPayload()],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->createPrediction(
            new CreatePredictionRequest(
                prediction: new CreatePrediction(
                    broadcasterId: '55696719',
                    title: 'Will there be any leaks today?',
                    outcomes: [['title' => 'Yes'], ['title' => 'No']],
                    predictionWindow: 600,
                ),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('', $request->getUri()->getQuery());

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame([
            'broadcaster_id'    => '55696719',
            'title'             => 'Will there be any leaks today?',
            'outcomes'          => [['title' => 'Yes'], ['title' => 'No']],
            'prediction_window' => 600,
        ], $body);

        $this->assertInstanceOf(PredictionResponse::class, $response);
        $this->assertInstanceOf(Prediction::class, $response->data[0]);
    }

    #[Test]
    public function end_prediction_omits_a_null_winning_outcome_when_cancelled(): void
    {
        $payload = $this->activePredictionPayload();
        $payload['status'] = 'CANCELED';
        $payload['ended_at'] = '2021-04-28T16:08:06.320848689Z';

        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => [$payload]], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->endPrediction(
            new EndPredictionRequest(
                prediction: new EndPrediction(
                    broadcasterId: '55696719',
                    id: 'd6676d5c-c86e-44d2-bfc4-100fb48f0656',
                    status: 'CANCELED',
                ),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('PATCH', $request->getMethod());

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame([
            'broadcaster_id' => '55696719',
            'id'             => 'd6676d5c-c86e-44d2-bfc4-100fb48f0656',
            'status'         => 'CANCELED',
        ], $body);

        $this->assertSame('CANCELED', $response->data[0]->status);
    }

    #[Test]
    public function end_prediction_sends_the_winning_outcome_when_resolved(): void
    {
        $payload = $this->activePredictionPayload();
        $payload['status'] = 'RESOLVED';
        $payload['winning_outcome_id'] = '021e9234-5893-49b4-982e-cfe9a0aaddd9';
        $payload['ended_at'] = '2021-04-28T16:08:06.320848689Z';

        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => [$payload]], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->endPrediction(
            new EndPredictionRequest(
                prediction: new EndPrediction(
                    broadcasterId: '55696719',
                    id: 'd6676d5c-c86e-44d2-bfc4-100fb48f0656',
                    status: 'RESOLVED',
                    winningOutcomeId: '021e9234-5893-49b4-982e-cfe9a0aaddd9',
                ),
            ),
            new StaticAccessToken(),
        );

        $body = json_decode((string) $http->getLastRequest()->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('021e9234-5893-49b4-982e-cfe9a0aaddd9', $body['winning_outcome_id']);
    }
}
