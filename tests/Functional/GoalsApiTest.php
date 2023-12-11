<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use DateTimeImmutable;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use League\OAuth2\Client\Token\AccessToken;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\GoalsApi;
use SimplyStream\TwitchApi\Helix\Models\Goals\CreatorGoal;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;

class GoalsApiTest extends UserAwareFunctionalTestCase
{
    public function testGetCreatorGoals(): void
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

        $goalsApi = new GoalsApi($apiClient);
        $getCreatorGoalsResponse = $goalsApi->getCreatorGoals(
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:read:goals']))
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $getCreatorGoalsResponse);
        $this->assertContainsOnlyInstancesOf(CreatorGoal::class, $getCreatorGoalsResponse->getData());
        $this->assertGreaterThan(0, count($getCreatorGoalsResponse->getData()));

        foreach ($getCreatorGoalsResponse->getData() as $creatorGoal) {
            $this->assertIsString($creatorGoal->getId());
            $this->assertNotEmpty($creatorGoal->getId());

            $this->assertSame($testUser['id'], $creatorGoal->getBroadcasterId());
            $this->assertSame($testUser['display_name'], $creatorGoal->getBroadcasterName());
            $this->assertSame($testUser['login'], $creatorGoal->getBroadcasterLogin());

            $this->assertIsString($creatorGoal->getType());
            $this->assertNotEmpty($creatorGoal->getType());
            $this->assertIsString($creatorGoal->getDescription());
            $this->assertNotEmpty($creatorGoal->getDescription());
            $this->assertIsInt($creatorGoal->getCurrentAmount());
            $this->assertGreaterThan(0, $creatorGoal->getCurrentAmount());
            $this->assertIsInt($creatorGoal->getTargetAmount());
            $this->assertGreaterThan(0, $creatorGoal->getTargetAmount());
            $this->assertInstanceOf(DateTimeImmutable::class, $creatorGoal->getCreatedAt());
        }
    }
}
