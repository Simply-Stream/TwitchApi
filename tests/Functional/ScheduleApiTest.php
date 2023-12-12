<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use DateTimeImmutable;
use GuzzleHttp\Client;
use League\OAuth2\Client\Token\AccessToken;
use Nyholm\Psr7\Factory\Psr17Factory;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\ScheduleApi;
use SimplyStream\TwitchApi\Helix\Models\Schedule\Category;
use SimplyStream\TwitchApi\Helix\Models\Schedule\CreateChannelStreamScheduleSegmentRequest;
use SimplyStream\TwitchApi\Helix\Models\Schedule\ScheduleSegment;
use SimplyStream\TwitchApi\Helix\Models\Schedule\Vacation;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;

class ScheduleApiTest extends UserAwareFunctionalTestCase
{
    public function testGetChannelStreamSchedule()
    {
        $this->markTestSkipped('"/schedule" mock api endpoint returns faulty data');

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

        $scheduleApi = new ScheduleApi($apiClient);
        $getChannelStreamSchedule = $scheduleApi->getChannelStreamSchedule(
            $testUser['id'],
            $accessToken
        );

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $getChannelStreamSchedule);
        $channelStreamScheduleData = $getChannelStreamSchedule->getData();
        $this->assertCount(20, $channelStreamScheduleData->getSegments());
        $this->assertContainsOnlyInstancesOf(ScheduleSegment::class, $channelStreamScheduleData->getSegments());

        foreach ($channelStreamScheduleData->getSegments() as $segment) {
            $this->assertIsString($segment->getId());
            $this->assertNotEmpty($segment->getId());
            $this->assertInstanceOf(DateTimeImmutable::class, $segment->getStartTime());
            $this->assertInstanceOf(DateTimeImmutable::class, $segment->getEndTime());
            $this->assertSame('Test Title', $segment->getTitle());
            $this->assertInstanceOf(Category::class, $segment->getCategory());
            $this->assertIsBool($segment->isRecurring());
            $this->assertNull($segment->getCanceledUntil());
        }

        $this->assertEquals($testUser['id'], $channelStreamScheduleData->getBroadcasterId());
        $this->assertEquals($testUser['display_name'], $channelStreamScheduleData->getBroadcasterName());
        $this->assertEquals($testUser['login'], $channelStreamScheduleData->getBroadcasterLogin());
        $this->assertInstanceOf(Vacation::class, $channelStreamScheduleData->getVacation());
    }

    public function testGetChannelICalendar()
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

        $scheduleApi = new ScheduleApi($apiClient);
        $getChannelICalendarResponse = $scheduleApi->getChannelICalendar($testUser['id']);

        $this->assertInstanceOf(TwitchDataResponse::class, $getChannelICalendarResponse);
        $this->assertIsString($getChannelICalendarResponse->getData());
    }

    public function testUpdateChannelStreamSchedule()
    {
        $this->expectNotToPerformAssertions();

        $testUser = $this->users[0];
        $accessToken = new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:manage:schedule']));
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

        $scheduleApi = new ScheduleApi($apiClient);
        $scheduleApi->updateChannelStreamSchedule(
            $testUser['id'],
            $accessToken,
            true,
            (new \DateTime('+ 1 week')),
            (new \DateTime('+ 10 week')),
            'Europe/Berlin'
        );
    }

    public function testCreateChannelStreamScheduleSegment()
    {
        $this->markTestSkipped('Assertions are missing');

        $testUser = $this->users[0];
        $accessToken = new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:manage:schedule']));
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

        $scheduleApi = new ScheduleApi($apiClient);
        $createChannelStreamScheduleSegment = $scheduleApi->createChannelStreamScheduleSegment(
            $testUser['id'],
            new CreateChannelStreamScheduleSegmentRequest(
                new \DateTimeImmutable(),
                'Europe/Berlin',
                "100"
            ),
            $accessToken
        );
    }
}
