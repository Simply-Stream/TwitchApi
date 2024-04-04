<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use League\OAuth2\Client\Token\AccessToken;
use Nyholm\Psr7\Factory\Psr17Factory;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\VideosApi;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;
use SimplyStream\TwitchApi\Helix\Models\Videos\MutedSegment;
use SimplyStream\TwitchApi\Helix\Models\Videos\Video;
use SimplyStream\TwitchApi\Tests\Helper\UserAwareFunctionalTestCase;

class VideosApiTest extends UserAwareFunctionalTestCase
{
    public function testGetVideos()
    {
        $testUser = $this->users[0];
        $accessToken = new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:manage:videos']));
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

        $videosApi = new VideosApi($apiClient);
        $videosResponse = $videosApi->getVideos($accessToken, userId: $testUser['id']);

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $videosResponse);
        $this->assertIsArray($videosResponse->getData());
        $this->assertCount(1, $videosResponse->getData());
        $video = $videosResponse->getData()[0];

        $this->assertInstanceOf(Video::class, $video);
        $this->assertSame($testUser['id'], $video->getUserId());
        $this->assertSame($testUser['display_name'], $video->getUserName());
        $this->assertSame($testUser['login'], $video->getUserLogin());
        $this->assertIsString($video->getId());
        $this->assertNotEmpty($video->getId());
        $this->assertIsString($video->getStreamId());
        $this->assertNotEmpty($video->getStreamId());
        $this->assertSame('Sample stream!', $video->getTitle());
        $this->assertSame('Such an interesting stream today...', $video->getDescription());
        $this->assertInstanceOf(\DateTimeInterface::class, $video->getCreatedAt());
        $this->assertInstanceOf(\DateTimeInterface::class, $video->getPublishedAt());
        $this->assertIsString($video->getUrl());
        $this->assertNotEmpty($video->getUrl());
        $this->assertIsString($video->getThumbnailUrl());
        $this->assertNotEmpty($video->getThumbnailUrl());
        $this->assertIsString($video->getViewable());
        $this->assertNotEmpty($video->getViewable());
        $this->assertSame(0, $video->getViewCount());
        $this->assertIsString($video->getLanguage());
        $this->assertNotEmpty($video->getLanguage());
        $this->assertIsString($video->getType());
        $this->assertNotEmpty($video->getType());
        $this->assertIsString($video->getDuration());
        $this->assertNotEmpty($video->getDuration());
        $this->assertIsArray($video->getMutedSegments());
        $this->assertContainsOnlyInstancesOf(MutedSegment::class, $video->getMutedSegments());

        foreach ($video->getMutedSegments() as $mutedSegment) {
            $this->assertIsInt($mutedSegment->getOffset());
            $this->assertIsInt($mutedSegment->getDuration());
            $this->assertNotEmpty($mutedSegment->getDuration());
        }
    }

    public function testDeleteVideos()
    {
        $testUser = $this->users[0];
        $accessToken = new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:manage:videos']));
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

        $videosApi = new VideosApi($apiClient);
        $videosResponse = $videosApi->getVideos($accessToken, userId: $testUser['id']);
        $deletedVideos = $videosApi->deleteVideos($videosResponse->getData()[0]->getId(), $accessToken);

        $this->assertInstanceOf(TwitchDataResponse::class, $deletedVideos);
        $this->assertIsArray($deletedVideos->getData());
        $this->assertCount(1, $deletedVideos->getData());
        $this->assertSame($videosResponse->getData()[0]->getId(), $deletedVideos->getData()[0]);
    }
}
