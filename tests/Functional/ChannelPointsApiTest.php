<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\Attributes\CoversClass;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\ChannelPointsApi;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\CreateCustomRewardRequest;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\CustomReward;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;

#[CoversClass(ChannelPointsApi::class)]
class ChannelPointsApiTest extends UserAwareFunctionalTestCase
{
    public function testCreateCustomRewards()
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

        $channelPointsApi = new ChannelPointsApi($apiClient);
        $createCustomRewardsResponse = $channelPointsApi->createCustomRewards(
            $this->users[0]['id'],
            new CreateCustomRewardRequest('Custom reward', 100),
            new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['channel:manage:redemptions']))
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $createCustomRewardsResponse);

        $this->assertCount(1, $createCustomRewardsResponse->getData());
        $this->assertContainsOnlyInstancesOf(CustomReward::class, $createCustomRewardsResponse->getData());
        $customReward = $createCustomRewardsResponse->getData()[0];

        $this->assertSame($this->users[0]['id'], $customReward->getBroadcasterId());
        $this->assertSame($this->users[0]['login'], $customReward->getBroadcasterLogin());
        $this->assertSame($this->users[0]['display_name'], $customReward->getBroadcasterName());
        $this->assertSame(sprintf('Fake reward for %s', $this->users[0]['id']), $customReward->getTitle());

        $channelPointsApi->deleteCustomRewards(
            $this->users[0]['id'],
            $customReward->getId(),
            new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['channel:manage:redemptions']))
        );
    }

    public function testDeleteCustomReward()
    {
        // This error is successful, when no exception it thrown
        $this->expectNotToPerformAssertions();

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

        $channelPointsApi = new ChannelPointsApi($apiClient);

        // Create a custom reward to delete it
        $customReward = $channelPointsApi->createCustomRewards(
            $this->users[0]['id'],
            new CreateCustomRewardRequest('Custom reward', 100),
            new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['channel:manage:redemptions']))
        );

        $channelPointsApi->deleteCustomRewards(
            $this->users[0]['id'],
            $customReward->getData()[0]->getId(),
            new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['channel:manage:redemptions']))
        );
    }

    public function testGetCustomReward()
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

        $channelPointsApi = new ChannelPointsApi($apiClient);
        $customRewardResponse = $channelPointsApi->getCustomReward(
            $this->users[0]['id'],
            new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['channel:read:redemptions']))
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $customRewardResponse);
        $this->assertContainsOnlyInstancesOf(CustomReward::class, $customRewardResponse->getData());
        $this->assertCount(1, $customRewardResponse->getData());
    }

    public function testGetCustomRewardRedemption()
    {
        $this->markTestSkipped(
            'The mock-api response is not correct. This test will be valid, when CustomRewardRedemptionResponses are fixed'
        );

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

        $channelPointsApi = new ChannelPointsApi($apiClient);
        $customRewardResponse = $channelPointsApi->getCustomReward(
            $this->users[0]['id'],
            new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['channel:read:redemptions']))
        );

        $getCustomRewardRedemptionResponse = $channelPointsApi->getCustomRewardRedemption(
            $this->users[0]['id'],
            $customRewardResponse->getData()[0]->getId(),
            new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['channel:read:redemptions'])),
            'FULFILLED'
        );
    }
}
