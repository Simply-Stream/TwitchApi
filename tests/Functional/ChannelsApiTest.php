<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use League\OAuth2\Client\Token\AccessToken;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\ChannelsApi;
use SimplyStream\TwitchApi\Helix\Models\Channels\ChannelInformation;
use SimplyStream\TwitchApi\Helix\Models\Channels\ModifyChannelInformationRequest;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Tests\Helper\UserAwareFunctionalTestCase;

class ChannelsApiTest extends UserAwareFunctionalTestCase
{
    public function testGetChannelInformation()
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

        $channelsApi = new ChannelsApi($apiClient);
        $channelInformationResponse = $channelsApi->getChannelInformation(
            [$testUser['id']],
            new AccessToken($this->appAccessToken)
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $channelInformationResponse);
        $channelInformation = $channelInformationResponse->getData();
        $this->assertContainsOnlyInstancesOf(ChannelInformation::class, $channelInformation);
        $this->assertCount(1, $channelInformation);

        $channelInfo = $channelInformation[0];
        $this->assertSame($testUser['id'], $channelInfo->getBroadcasterId());
        $this->assertSame($testUser['login'], $channelInfo->getBroadcasterLogin());
        $this->assertSame($testUser['display_name'], $channelInfo->getBroadcasterName());
        $this->assertSame($testUser['stream_language'], $channelInfo->getBroadcasterLanguage());
        $this->assertSame($testUser['game_id']['String'], $channelInfo->getGameId());
        // This is empty, even though game_id is set ¯\_(ツ)_/¯
        // $this->assertSame($this->users[0]['game_name'], $channelInformation[0]->getGameName());
        $this->assertSame('Sample stream!', $channelInfo->getTitle());
        $this->assertSame(0, $channelInfo->getDelay());
        $this->assertSame(['English', 'CLI Tag'], $channelInfo->getTags());
        $this->assertSame([], $channelInfo->getContentClassificationLabels());
        $this->assertFalse($channelInfo->isBrandedContent());
    }

    public function testModifyChannelInformation()
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

        $channelsApi = new ChannelsApi($apiClient);
        $channelInformationResponse = $channelsApi->getChannelInformation(
            [$testUser['id']],
            new AccessToken($this->appAccessToken)
        );

        $this->assertSame('en', $channelInformationResponse->getData()[0]->getBroadcasterLanguage());

        $channelsApi->modifyChannelInformation(
            $this->users[0]['id'],
            new ModifyChannelInformationRequest(broadcasterLanguage: 'de'),
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:manage:broadcast']))
        );

        $channelInformationResponse = $channelsApi->getChannelInformation(
            [$testUser['id']],
            new AccessToken($this->appAccessToken)
        );

        $this->assertSame('de', $channelInformationResponse->getData()[0]->getBroadcasterLanguage());

        $channelsApi->modifyChannelInformation(
            $this->users[0]['id'],
            new ModifyChannelInformationRequest(broadcasterLanguage: 'en'),
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:manage:broadcast']))
        );

        $channelInformationResponse = $channelsApi->getChannelInformation(
            [$testUser['id']],
            new AccessToken($this->appAccessToken)
        );

        $this->assertSame('en', $channelInformationResponse->getData()[0]->getBroadcasterLanguage());
    }

    public function testGetFollowedChannels()
    {
        $this->markTestSkipped('FollowedChannels endpoint is currently not implemented by the twitch mock-api');

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

        $channelsApi = new ChannelsApi($apiClient);
        $channelFollowersResponse = $channelsApi->getChannelFollowers(
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['user:read:follows']))
        );
    }
}
