<?php

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\EventSubApi;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions\ChannelFollowSubscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

class EventSubApiTest extends UserAwareFunctionalTestCase
{
    public function testCreateEventsubSubscription()
    {
        $this->markTestSkipped('"eventsub/subscriptions" endpoint not yet implemented');

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

        $eventSubApi = new EventSubApi($apiClient);
        $createEventSubSubscriptionResponse = $eventSubApi->createEventSubSubscription(
            new ChannelFollowSubscription(
                ['broadcasterUserId' => $testUser['id'], 'moderatorUserId' => $testUser['id']],
                new Transport('webhook')
            )
        );
    }
}
