<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use League\OAuth2\Client\Token\AccessToken;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\RaidsApi;
use SimplyStream\TwitchApi\Helix\Models\Raids\Raid;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;

class RaidsApiTest extends UserAwareFunctionalTestCase
{
    public function testStartRaid()
    {
        $this->markTestSkipped('"/raids" mock-api endpoint currently returns a faulty structure');

        $fromUser = $this->users[0];
        $toUser = $this->users[1];
        $accessToken = new AccessToken($this->getAccessTokenForUser($fromUser['id'], ['channel:manage:raids']));
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

        $raidsApi = new RaidsApi($apiClient);
        $startRaidResponse = $raidsApi->startRaid($fromUser['id'], $toUser['id'], $accessToken);

        $this->assertInstanceOf(TwitchDataResponse::class, $startRaidResponse);
        $this->assertCount(1, $startRaidResponse->getData());
        $this->assertContainsOnlyInstancesOf(Raid::class, $startRaidResponse->getData());

        $this->assertInstanceOf(\DateTimeImmutable::class, $startRaidResponse->getData()[0]->getCreatedAt());
        $this->assertIsBool($startRaidResponse->getData()[0]->isMature());
    }

    public function testCancelRaid()
    {
        $this->expectNotToPerformAssertions();

        $fromUser = $this->users[0];
        $accessToken = new AccessToken($this->getAccessTokenForUser($fromUser['id'], ['channel:manage:raids']));
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

        $raidsApi = new RaidsApi($apiClient);
        $raidsApi->cancelRaid($fromUser['id'], $accessToken);
    }
}
