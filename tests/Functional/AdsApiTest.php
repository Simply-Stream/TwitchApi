<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\Attributes\CoversClass;
use SimplyStream\TwitchApi\Helix\Api\AdsApi;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Models\Ads\Commercial;
use SimplyStream\TwitchApi\Helix\Models\Ads\StartCommercialRequest;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;

#[CoversClass(AdsApi::class)]
class AdsApiTest extends UserAwareFunctionalTestCase
{
    public function testStartCommercial()
    {
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

        $adsApi = new AdsApi($apiClient);
        $startCommercialResponse = $adsApi->startCommercial(
            new StartCommercialRequest($this->users[0]['id'], 30),
            new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['channel:edit:commercial']))
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $startCommercialResponse);
        $this->assertIsArray($startCommercialResponse->getData());

        foreach ($startCommercialResponse->getData() as $commercial) {
            $this->assertInstanceOf(Commercial::class, $commercial);
            // This can locally sometimes result in a false positive, when a startCommercial call has been called already.
            $this->assertSame("", $commercial->getMessage());
            $this->assertSame(30, $commercial->getLength());
            $this->assertSame(480, $commercial->getRetryAfter());
        }
    }

    public function testGetAdSchedule()
    {
        $this->markTestSkipped('Endpoint "/helix/channels/ads" is not implemented yet in mock-api');

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

        $adsApi = new AdsApi($apiClient);
        $getAdScheduleResponse = $adsApi->getAdSchedule(
            $this->users[0]['id'],
            new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['channel:read:ads scope']))
        );
    }

    public function testSnoozeNextAd()
    {
        $this->markTestSkipped('Endpoint "/helix/channels/ads/schedule/snooze" is not implemented yet in mock-api');

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

        $adsApi = new AdsApi($apiClient);
        $getAdScheduleResponse = $adsApi->snoozeNextAd(
            $this->users[0]['id'],
            new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['channel:manage:ads']))
        );
    }
}
