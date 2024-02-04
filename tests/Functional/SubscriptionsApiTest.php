<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use League\OAuth2\Client\Token\AccessToken;
use Nyholm\Psr7\Factory\Psr17Factory;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\SubscriptionsApi;
use SimplyStream\TwitchApi\Helix\Models\Subscriptions\Subscription;
use SimplyStream\TwitchApi\Helix\Models\Subscriptions\TwitchPaginatedSubPointsResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;
use SimplyStream\TwitchApi\Tests\Helper\UserAwareFunctionalTestCase;

class SubscriptionsApiTest extends UserAwareFunctionalTestCase
{
    public function testGetBroadcasterSubscriptions()
    {
        $testUser = $this->users[0];
        $accessToken = new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:read:subscriptions']));
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

        $subscriptionsApi = new SubscriptionsApi($apiClient);
        $getBroadcasterSubscriptionsResponse = $subscriptionsApi->getBroadcasterSubscriptions(
            $testUser['id'],
            $accessToken
        );

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $getBroadcasterSubscriptionsResponse);
        $subscriptions = $getBroadcasterSubscriptionsResponse->getData();
        $this->assertIsArray($subscriptions);
        // Total could be bigger than the default limit of 20 items
        $this->assertLessThanOrEqual($getBroadcasterSubscriptionsResponse->getTotal(), count($subscriptions));
        $this->assertContainsOnlyInstancesOf(Subscription::class, $subscriptions);

        foreach ($subscriptions as $subscription) {
            $this->assertInstanceOf(Subscription::class, $subscription);
            $this->assertEquals($testUser['id'], $subscription->getBroadcasterId());
            $this->assertEquals($testUser['login'], $subscription->getBroadcasterLogin());
            $this->assertEquals($testUser['display_name'], $subscription->getBroadcasterName());
            $this->assertIsBool($subscription->isGift());

            if ($subscription->isGift()) {
                $this->assertNotEmpty($subscription->getGifterId());
                $this->assertNotEmpty($subscription->getGifterLogin());
                $this->assertNotEmpty($subscription->getGifterName());
            }

            // @TODO: Disabled for now. Can be reactivated, when I found a way to switch between null or empty string,
            //        depending if the response is from checkUserSubscriptions or broadcasterSubscriptions in mock api
            // $this->assertIsString($subscription->getGifterId());
            // $this->assertIsString($subscription->getGifterLogin());
            // $this->assertIsString($subscription->getGifterName());
            $this->assertIsString($subscription->getTier());
            $this->assertNotEmpty($subscription->getTier());
            $this->assertIsString($subscription->getPlanName());
            $this->assertIsString($subscription->getUserId());
            $this->assertIsString($subscription->getUserName());
            $this->assertIsString($subscription->getUserLogin());
        }
    }

    public function testCheckUserSubscription()
    {
        $testUser = $this->users[0];
        $accessToken = new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:read:subscriptions']));
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

        $subscriptionsApi = new SubscriptionsApi($apiClient);
        $getBroadcasterSubscriptionsResponse = $subscriptionsApi->getBroadcasterSubscriptions(
            $testUser['id'],
            $accessToken,
            first: 100
        );

        $checkUserSubscription = $subscriptionsApi->checkUserSubscription(
            $testUser['id'],
            $getBroadcasterSubscriptionsResponse->getData()[0]->getUserId(),
            $accessToken
        );

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $checkUserSubscription);
        $this->assertIsArray($checkUserSubscription->getData());
        $this->assertCount(1, $checkUserSubscription->getData());
        $this->assertEquals($getBroadcasterSubscriptionsResponse->getData()[0], $checkUserSubscription->getData()[0]);
    }
}
