<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use League\OAuth2\Client\Token\AccessToken;
use SimplyStream\TwitchApi\Helix\Api\AnalyticsApi;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;

class AnalyticsApiTest extends UserAwareFunctionalTestCase
{
    public function testGetExtensionAnalytics()
    {
        $this->markTestSkipped('Endpoint "/helix/analytics/extensions" is not implemented (yet) in mock-api');

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

        $analyticsApi = new AnalyticsApi($apiClient);
        $getExtensionAnalyticsResponse = $analyticsApi->getExtensionAnalytics(new AccessToken($this->appAccessToken));
    }

    public function testGetGameAnalytics()
    {
        $this->markTestSkipped('Endpoint "/helix/analytics/games" is not implemented (yet) in mock-api');

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

        $analyticsApi = new AnalyticsApi($apiClient);
        $getGameAnalyticsResponse = $analyticsApi->getGameAnalytics(new AccessToken($this->appAccessToken));
    }
}
