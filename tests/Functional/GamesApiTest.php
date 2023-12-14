<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Helix\Api;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use League\OAuth2\Client\Token\AccessToken;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\GamesApi;
use SimplyStream\TwitchApi\Helix\Models\Games\Game;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;
use SimplyStream\TwitchApi\Tests\Helper\UserAwareFunctionalTestCase;

class GamesApiTest extends UserAwareFunctionalTestCase
{
    public function testGetTopGames()
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

        $gamesApi = new GamesApi($apiClient);
        $getTopGamesResponse = $gamesApi->getTopGames(new AccessToken($this->appAccessToken));

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $getTopGamesResponse);
        $this->assertContainsOnlyInstancesOf(Game::class, $getTopGamesResponse->getData());
        $this->assertGreaterThan(0, count($getTopGamesResponse->getData()));
        $this->assertNull($getTopGamesResponse->getPagination());
        $this->assertNull($getTopGamesResponse->getTotal());
    }

    public function testGetGames()
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

        $gamesApi = new GamesApi($apiClient);
        $getGamesResponse = $gamesApi->getGames(new AccessToken($this->appAccessToken), name: ['Just Chatting']);

        $this->assertInstanceOf(TwitchDataResponse::class, $getGamesResponse);
        $this->assertContainsOnlyInstancesOf(Game::class, $getGamesResponse->getData());
        $this->assertCount(1, $getGamesResponse->getData());

        foreach ($getGamesResponse->getData() as $game) {
            $this->assertIsString($game->getId());
            $this->assertNotEmpty($game->getId());
            $this->assertSame('Just Chatting', $game->getName());
            $this->assertSame('https://static-cdn.jtvnw.net/ttv-boxart/Just%20Chatting-{width}x{height}.jpg', $game->getBoxArtUrl());
            $this->assertIsString($game->getIgdbId());
            $this->assertNotEmpty($game->getIgdbId());
        }
    }
}
