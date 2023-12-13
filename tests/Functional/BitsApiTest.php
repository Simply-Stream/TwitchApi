<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\Attributes\CoversClass;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\BitsApi;
use SimplyStream\TwitchApi\Helix\Models\Bits\BitsLeaderboard;
use SimplyStream\TwitchApi\Helix\Models\Bits\Cheermote;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchDateRangeDataResponse;

#[CoversClass(BitsApi::class)]
class BitsApiTest extends UserAwareFunctionalTestCase
{
    public function testGetBitsLeaderboard()
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

        $bitsApi = new BitsApi($apiClient);
        $bitsLeaderboardResponse = $bitsApi->getBitsLeaderboard(
            new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['bits:read']))
        );

        $this->assertInstanceOf(TwitchDateRangeDataResponse::class, $bitsLeaderboardResponse);
        $this->assertIsInt($bitsLeaderboardResponse->getTotal());
        $this->assertCount($bitsLeaderboardResponse->getTotal(), $bitsLeaderboardResponse->getData());
        $this->assertContainsOnlyInstancesOf(BitsLeaderboard::class, $bitsLeaderboardResponse->getData());

        for ($i = 0; $i < $bitsLeaderboardResponse->getTotal(); $i++) {
            $rank = $bitsLeaderboardResponse->getData()[$i];
            $this->assertSame($i + 1, $rank->getRank());
            $this->assertIsInt($rank->getScore());
            $this->assertGreaterThan(0, $rank->getScore());
        }
    }

    public function testGetCheermotes()
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

        $bitsApi = new BitsApi($apiClient);
        $getCheermotesResponse = $bitsApi->getCheermotes(new AccessToken($this->appAccessToken), $this->users[0]['id']);

        $this->assertInstanceOf(TwitchDataResponse::class, $getCheermotesResponse);
        $this->assertContainsOnlyInstancesOf(Cheermote::class, $getCheermotesResponse->getData());
        $this->assertGreaterThan(0, count($getCheermotesResponse->getData()));
    }

    public function testGetExtensionTransactions()
    {
        $this->markTestSkipped('There is no extension implemented in the mock-api');

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

        $bitsApi = new BitsApi($apiClient);
        $getExtensionTransactions = $bitsApi->getExtensionTransactions(
            new AccessToken(
                $this->appAccessToken
            ),
            ''
        );
    }
}
