<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use DateTimeImmutable;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use League\OAuth2\Client\Token\AccessToken;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\PredictionsApi;
use SimplyStream\TwitchApi\Helix\Models\Predictions\CreatePredictionRequest;
use SimplyStream\TwitchApi\Helix\Models\Predictions\EndPredictionRequest;
use SimplyStream\TwitchApi\Helix\Models\Predictions\Prediction;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;
use SimplyStream\TwitchApi\Tests\Helper\UserAwareFunctionalTestCase;

class PredictionsApiTest extends UserAwareFunctionalTestCase
{
    public function testGetPredictions()
    {
        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $predictionsApi = new PredictionsApi($apiClient);
        $getPredictionsResponse = $predictionsApi->getPredictions(
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:manage:predictions']))
        );

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $getPredictionsResponse);
        $this->assertCount(1, $getPredictionsResponse->getData());
        $this->assertContainsOnlyInstancesOf(Prediction::class, $getPredictionsResponse->getData());

        foreach ($getPredictionsResponse->getData() as $prediction) {
            $this->assertInstanceOf(Prediction::class, $prediction);
            $this->assertIsString($prediction->getId());
            $this->assertSame($testUser['id'], $prediction->getBroadcasterId());
            $this->assertSame($testUser['display_name'], $prediction->getBroadcasterName());
            $this->assertSame($testUser['login'], $prediction->getBroadcasterLogin());
            $this->assertSame('Test Prediction', $prediction->getTitle());
            $this->assertIsArray($prediction->getOutcomes());
            $this->assertIsInt($prediction->getPredictionWindow());
            $this->assertSame('ACTIVE', $prediction->getStatus());
            $this->assertInstanceOf(DateTimeImmutable::class, $prediction->getCreatedAt());
            $this->assertNull($prediction->getWinningOutcomeId());
            $this->assertNull($prediction->getEndedAt());
            $this->assertNull($prediction->getLockedAt());
        }
    }

    public function testCreatePrediction()
    {
        $outcomes = [['title' => 'Outcome 1'], ['title' => 'Outcome 2']];
        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $predictionsApi = new PredictionsApi($apiClient);
        $createPredictionResponse = $predictionsApi->createPrediction(
            new CreatePredictionRequest(
                $testUser['id'],
                'Test Prediction',
                $outcomes,
                300
            ),
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:manage:predictions']))
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $createPredictionResponse);
        $this->assertCount(1, $createPredictionResponse->getData());
        $this->assertContainsOnlyInstancesOf(Prediction::class, $createPredictionResponse->getData());

        foreach ($createPredictionResponse->getData() as $prediction) {
            $this->assertInstanceOf(Prediction::class, $prediction);
            $this->assertIsString($prediction->getId());
            $this->assertSame($testUser['id'], $prediction->getBroadcasterId());
            $this->assertSame($testUser['display_name'], $prediction->getBroadcasterName());
            $this->assertSame($testUser['login'], $prediction->getBroadcasterLogin());
            $this->assertSame('Test Prediction', $prediction->getTitle());
            $this->assertIsArray($prediction->getOutcomes());

            foreach ($prediction->getOutcomes() as $key => $outcome) {
                $this->assertSame('Outcome ' . $key + 1, $outcome->getTitle());
            }

            $this->assertIsInt($prediction->getPredictionWindow());
            $this->assertSame('ACTIVE', $prediction->getStatus());
            $this->assertInstanceOf(DateTimeImmutable::class, $prediction->getCreatedAt());
            $this->assertNull($prediction->getWinningOutcomeId());
            $this->assertNull($prediction->getEndedAt());
            $this->assertNull($prediction->getLockedAt());
        }
    }

    public function testEndPrediction()
    {
        $testUser = $this->users[0];
        $accessToken = new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:manage:predictions']));
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $predictionsApi = new PredictionsApi($apiClient);
        $getPredictionsResponse = $predictionsApi->getPredictions(
            $testUser['id'],
            $accessToken
        );

        $endPredictionResponse = $predictionsApi->endPrediction(
            new EndPredictionRequest(
                $testUser['id'],
                $getPredictionsResponse->getData()[0]->getId(),
                'CANCELED',
            ),
            $accessToken
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $endPredictionResponse);
        $this->assertCount(1, $endPredictionResponse->getData());
        $this->assertContainsOnlyInstancesOf(Prediction::class, $endPredictionResponse->getData());

        $this->assertSame(
            $getPredictionsResponse->getData()[0]->getId(),
            $endPredictionResponse->getData()[0]->getId()
        );
        $this->assertSame('CANCELED', $endPredictionResponse->getData()[0]->getStatus());
    }
}
