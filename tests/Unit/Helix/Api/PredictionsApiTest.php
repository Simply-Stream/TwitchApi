<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Predictions\Request\CreatePredictionRequest;
use SimplyStream\TwitchApi\Helix\Api\Predictions\Request\EndPredictionRequest;
use SimplyStream\TwitchApi\Helix\Api\Predictions\Request\GetPredictionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Predictions\Response\PredictionResponse;
use SimplyStream\TwitchApi\Helix\Api\Predictions\Response\PredictionsResponse;
use SimplyStream\TwitchApi\Helix\Api\PredictionsApi;
use SimplyStream\TwitchApi\Helix\Models\Predictions\CreatePrediction;
use SimplyStream\TwitchApi\Helix\Models\Predictions\EndPrediction;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(PredictionsApi::class)]
final class PredictionsApiTest extends TestCase
{
    private ApiClientInterface $apiClient;
    private DenormalizerInterface $denormalizer;
    private NormalizerInterface $normalizer;
    private StaticAccessToken $token;

    protected function setUp(): void
    {
        $this->apiClient = $this->createMock(ApiClientInterface::class);
        $this->denormalizer = $this->createMock(DenormalizerInterface::class);
        $this->normalizer = $this->createMock(NormalizerInterface::class);
        $this->token = new StaticAccessToken();
    }

    private function api(): PredictionsApi
    {
        return new PredictionsApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function get_predictions_omits_empty_ids_and_null_after(): void
    {
        $raw = ['data' => []];
        $expected = new PredictionsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'predictions', $this->token, [
                'broadcaster_id' => '1234',
                'first'          => 20,
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, PredictionsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getPredictions(new GetPredictionsRequest(broadcasterId: '1234'), $this->token),
        );
    }

    #[Test]
    public function get_predictions_repeats_ids(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'predictions', $this->token, [
                'broadcaster_id' => '1234',
                'id'             => ['pr1', 'pr2'],
                'first'          => 20,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new PredictionsResponse(data: []));

        $this->api()->getPredictions(
            new GetPredictionsRequest(broadcasterId: '1234', ids: ['pr1', 'pr2']),
            $this->token,
        );
    }

    #[Test]
    public function create_prediction_posts_normalized_payload(): void
    {
        $prediction = new CreatePrediction(
            broadcasterId: '1234',
            title: 'Will we win?',
            outcomes: [
                ['title' => 'Yes'],
                ['title' => 'No'],
            ],
            predictionWindow: 120,
        );
        $normalized = ['broadcaster_id' => '1234', 'title' => 'Will we win?', 'prediction_window' => 120];
        $raw = ['data' => []];
        $expected = new PredictionResponse(data: []);

        $this->normalizer->expects($this->once())->method('normalize')->with($prediction)->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'predictions', $this->token, [], $normalized)
            ->willReturn($raw);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->createPrediction(new CreatePredictionRequest(prediction: $prediction), $this->token),
        );
    }

    #[Test]
    public function end_prediction_patches_normalized_payload(): void
    {
        $prediction = new EndPrediction(
            broadcasterId: '1234',
            id: 'pred-1',
            status: 'CANCELED',
        );
        $normalized = ['broadcaster_id' => '1234', 'id' => 'pred-1', 'status' => 'CANCELED'];
        $raw = ['data' => []];
        $expected = new PredictionResponse(data: []);

        $this->normalizer->expects($this->once())->method('normalize')->with($prediction)->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PATCH', 'predictions', $this->token, [], $normalized)
            ->willReturn($raw);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->endPrediction(new EndPredictionRequest(prediction: $prediction), $this->token),
        );
    }
}
