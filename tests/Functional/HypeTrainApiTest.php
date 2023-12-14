<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use League\OAuth2\Client\Token\AccessToken;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\HypeTrainApi;
use SimplyStream\TwitchApi\Helix\Models\HypeTrain\Contribution;
use SimplyStream\TwitchApi\Helix\Models\HypeTrain\EventData;
use SimplyStream\TwitchApi\Helix\Models\HypeTrain\HypeTrainEvent;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;
use SimplyStream\TwitchApi\Tests\Helper\UserAwareFunctionalTestCase;

class HypeTrainApiTest extends UserAwareFunctionalTestCase
{
    public function testGetHypeTrainEvents(): void
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

        $hypeTrainApi = new HypeTrainApi($apiClient);
        $getHypeTrainEventsResponse = $hypeTrainApi->getHypeTrainEvents(
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:read:hype_train']))
        );

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $getHypeTrainEventsResponse);
        $this->assertContainsOnlyInstancesOf(HypeTrainEvent::class, $getHypeTrainEventsResponse->getData());
        $this->assertGreaterThan(0, count($getHypeTrainEventsResponse->getData()));

        foreach ($getHypeTrainEventsResponse->getData() as $hypeTrainEvent) {
            $eventData = $hypeTrainEvent->getEventData();
            $this->assertIsString($hypeTrainEvent->getId());
            $this->assertNotEmpty($hypeTrainEvent->getId());
            $this->assertIsString($hypeTrainEvent->getEventType());
            $this->assertNotEmpty($hypeTrainEvent->getEventType());
            $this->assertInstanceOf(\DateTimeImmutable::class, $hypeTrainEvent->getEventTimestamp());
            $this->assertIsString($hypeTrainEvent->getVersion());
            $this->assertNotEmpty($hypeTrainEvent->getVersion());
            $this->assertInstanceOf(EventData::class, $eventData);

            // EventData
            $this->assertSame($testUser['id'], $eventData->getBroadcasterId());
            $this->assertInstanceOf(\DateTimeImmutable::class, $eventData->getCooldownEndTime());
            $this->assertInstanceOf(\DateTimeImmutable::class, $eventData->getExpiresAt());
            $this->assertGreaterThan(0, $eventData->getGoal());
            $this->assertIsString($eventData->getId());

            // LastContribution
            $lastContribution = $eventData->getLastContribution();
            $this->assertInstanceOf(Contribution::class, $lastContribution);
            $this->assertGreaterThan(0, $lastContribution->getTotal());
            $this->assertContains($lastContribution->getType(), ['BITS', 'SUBS', 'OTHER']);
            $this->assertIsString($lastContribution->getUser());
            //

            $this->assertGreaterThan(0, $eventData->getLevel());
            $this->assertInstanceOf(\DateTimeImmutable::class, $eventData->getStartedAt());

            // TopContributions
            $this->assertIsArray($eventData->getTopContributions());
            $this->assertNotEmpty($eventData->getTopContributions());
            foreach ($eventData->getTopContributions() as $contribution) {
                $this->assertInstanceOf(Contribution::class, $contribution);
                $this->assertGreaterThan(0, $contribution->getTotal());
                $this->assertContains($contribution->getType(), ['BITS', 'SUBS', 'OTHER']);
                $this->assertIsString($contribution->getUser());
            }
            //

            $this->assertGreaterThan(0, $eventData->getTotal());
            // EventData
        }
    }
}
