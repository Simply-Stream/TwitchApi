<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use League\OAuth2\Client\Token\AccessToken;
use Nyholm\Psr7\Factory\Psr17Factory;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\TeamsApi;
use SimplyStream\TwitchApi\Helix\Models\Teams\ChannelTeam;
use SimplyStream\TwitchApi\Helix\Models\Teams\Member;
use SimplyStream\TwitchApi\Helix\Models\Teams\Team;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;

class TeamsApiTest extends UserAwareFunctionalTestCase
{
    public function testGetTeams()
    {
        $accessToken = new AccessToken($this->appAccessToken);
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

        $teamsApi = new TeamsApi($apiClient);
        $getTeamsResponse = $teamsApi->getTeams($accessToken, 'clidev');

        $this->assertInstanceOf(TwitchDataResponse::class, $getTeamsResponse);
        $this->assertIsArray($getTeamsResponse->getData());
        $this->assertContainsOnlyInstancesOf(Team::class, $getTeamsResponse->getData());
        $this->assertGreaterThan(0, count($getTeamsResponse->getData()));

        $team = $getTeamsResponse->getData()[0];
        $this->assertSame('clidev', $team->getTeamName());
        $this->assertSame('CLI Developers', $team->getTeamDisplayName());
        $this->assertInstanceOf(\DateTimeImmutable::class, $team->getCreatedAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $team->getUpdatedAt());
        $this->assertIsArray($team->getUsers());
        $this->assertContainsOnlyInstancesOf(Member::class, $team->getUsers());

        foreach ($team->getUsers() as $user) {
            $this->assertIsString($user->getUserId());
            $this->assertNotEmpty($user->getUserId());
            $this->assertIsString($user->getUserName());
            $this->assertNotEmpty($user->getUserName());
            $this->assertIsString($user->getUserLogin());
            $this->assertNotEmpty($user->getUserLogin());
        }

        $this->assertIsString($team->getInfo());
        $this->assertIsString($team->getThumbnailUrl());
        $this->assertIsString($team->getId());
        $this->assertNotEmpty($team->getId());
        $this->assertNull($team->getBackgroundImageUrl());
        $this->assertNull($team->getBanner());
    }

    public function testGetChannelTeams()
    {
        $testUser = $this->users[0];
        $accessToken = new AccessToken($this->appAccessToken);
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

        $teamsApi = new TeamsApi($apiClient);
        $getChannelTeamsResponse = $teamsApi->getChannelTeams($testUser['id'], $accessToken);

        $this->assertInstanceOf(TwitchDataResponse::class, $getChannelTeamsResponse);
        $this->assertIsArray($getChannelTeamsResponse->getData());
        $this->assertContainsOnlyInstancesOf(ChannelTeam::class, $getChannelTeamsResponse->getData());
        $this->assertGreaterThan(0, count($getChannelTeamsResponse->getData()));

        $team = $getChannelTeamsResponse->getData()[0];
        $this->assertIsString($team->getBroadcasterId());
        $this->assertNotEmpty($team->getBroadcasterId());
        $this->assertIsString($team->getBroadcasterName());
        $this->assertNotEmpty($team->getBroadcasterName());
        $this->assertIsString($team->getBroadcasterLogin());
        $this->assertNotEmpty($team->getBroadcasterLogin());
        $this->assertSame('clidev', $team->getTeamName());
        $this->assertSame('CLI Developers', $team->getTeamDisplayName());
        $this->assertInstanceOf(\DateTimeImmutable::class, $team->getCreatedAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $team->getUpdatedAt());
        $this->assertIsString($team->getInfo());
        $this->assertIsString($team->getThumbnailUrl());
        $this->assertIsString($team->getId());
        $this->assertNotEmpty($team->getId());
        $this->assertNull($team->getBackgroundImageUrl());
        $this->assertNull($team->getBanner());
    }
}
