<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use DateTimeImmutable;
use GuzzleHttp\Client;
use League\OAuth2\Client\Token\AccessToken;
use Nyholm\Psr7\Factory\Psr17Factory;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\StreamsApi;
use SimplyStream\TwitchApi\Helix\Models\Streams\CreateStreamMarkerRequest;
use SimplyStream\TwitchApi\Helix\Models\Streams\Marker;
use SimplyStream\TwitchApi\Helix\Models\Streams\Stream;
use SimplyStream\TwitchApi\Helix\Models\Streams\StreamKey;
use SimplyStream\TwitchApi\Helix\Models\Streams\StreamMarker;
use SimplyStream\TwitchApi\Helix\Models\Streams\Video;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;

class StreamsApiTest extends UserAwareFunctionalTestCase
{
    public function testGetStreams()
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

        $streamsApi = new StreamsApi($apiClient);
        $getStreamsResponse = $streamsApi->getStreams($accessToken);

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $getStreamsResponse);
        $streams = $getStreamsResponse->getData();
        $this->assertIsArray($streams);
        $this->assertContainsOnlyInstancesOf(Stream::class, $streams);

        foreach ($streams as $stream) {
            $this->assertInstanceOf(Stream::class, $stream);
            $this->assertInstanceOf(Stream::class, $stream);
            $this->assertIsString($stream->getId());
            $this->assertIsString($stream->getUserId());
            $this->assertIsString($stream->getUserLogin());
            $this->assertIsString($stream->getUserName());
            $this->assertIsString($stream->getGameId());
            $this->assertIsString($stream->getGameName());
            $this->assertIsString($stream->getType());
            $this->assertIsString($stream->getTitle());
            $this->assertIsArray($stream->getTags());
            $this->assertIsInt($stream->getViewerCount());
            $this->assertInstanceOf(DateTimeImmutable::class, $stream->getStartedAt());
            $this->assertIsString($stream->getLanguage());
            $this->assertIsString($stream->getThumbnailUrl());
            $this->assertIsBool($stream->isMature());
        }
    }

    public function testGetStreamKey()
    {
        $testUser = $this->users[0];
        $accessToken = new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:read:stream_key']));
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

        $streamsApi = new StreamsApi($apiClient);
        $getStreamKeyResponse = $streamsApi->getStreamKey($testUser['id'], $accessToken);

        $this->assertInstanceOf(TwitchDataResponse::class, $getStreamKeyResponse);
        $streamKey = $getStreamKeyResponse->getData();
        $this->assertIsArray($streamKey);
        $this->assertCount(1, $streamKey);
        $this->assertContainsOnlyInstancesOf(StreamKey::class, $streamKey);
        $this->assertStringStartsWith('live_', $streamKey[0]->getStreamKey());
    }

    public function testGetFollowedStreams()
    {
        $this->markTestSkipped('"/streams/followed" mock-api endpoint returns faulty data structure');

        $testUser = $this->users[0];
        $accessToken = new AccessToken($this->getAccessTokenForUser($testUser['id'], ['user:read:follows']));
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

        $streamsApi = new StreamsApi($apiClient);
        $getFollowedStreamsResponse = $streamsApi->getFollowedStreams($testUser['id'], $accessToken);
        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $getFollowedStreamsResponse);
        $streams = $getFollowedStreamsResponse->getData();
        $this->assertIsArray($streams);
        $this->assertContainsOnlyInstancesOf(Stream::class, $streams);

        foreach ($streams as $stream) {
            $this->assertInstanceOf(Stream::class, $stream);
            $this->assertIsString($stream->getId());
            $this->assertIsString($stream->getUserId());
            $this->assertIsString($stream->getUserLogin());
            $this->assertIsString($stream->getUserName());
            $this->assertIsString($stream->getGameId());
            $this->assertIsString($stream->getGameName());
            $this->assertIsString($stream->getType());
            $this->assertIsString($stream->getTitle());
            $this->assertIsArray($stream->getTags());
            $this->assertIsInt($stream->getViewerCount());
            $this->assertInstanceOf(DateTimeImmutable::class, $stream->getStartedAt());
            $this->assertIsString($stream->getLanguage());
            $this->assertIsString($stream->getThumbnailUrl());
            $this->assertIsBool($stream->isMature());
        }
    }

    public function testGetStreamMarkers()
    {
        $testUser = $this->users[0];
        $accessToken = new AccessToken($this->getAccessTokenForUser($testUser['id'], ['user:read:broadcast']));
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

        $streamsApi = new StreamsApi($apiClient);
        $getStreamMarkersResponse = $streamsApi->getStreamMarkers($accessToken, $testUser['id']);

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $getStreamMarkersResponse);
        $streamMarkers = $getStreamMarkersResponse->getData();
        $this->assertIsArray($streamMarkers);
        $this->assertContainsOnlyInstancesOf(StreamMarker::class, $streamMarkers);

        foreach ($streamMarkers as $streamMarker) {
            $this->assertSame($testUser['id'], $streamMarker->getUserId());
            $this->assertSame($testUser['display_name'], $streamMarker->getUserName());
            $this->assertSame($testUser['login'], $streamMarker->getUserLogin());

            $this->assertIsArray($streamMarker->getVideos());
            $this->assertContainsOnlyInstancesOf(Video::class, $streamMarker->getVideos());

            foreach ($streamMarker->getVideos() as $video) {
                $this->assertIsString($video->getVideoId());
                $this->assertNotEmpty($video->getVideoId());
                $this->assertIsArray($video->getMarkers());
            }
        }
    }

    public function testCreateStreamMarker()
    {
        $testUser = $this->users[0];
        $accessToken = new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:manage:broadcast']));
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

        $streamsApi = new StreamsApi($apiClient);
        $createStreamMarkerResponse = $streamsApi->createStreamMarker(
            new CreateStreamMarkerRequest($testUser['id'], 'Some marker'),
            $accessToken
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $createStreamMarkerResponse);
        $streamMarkers = $createStreamMarkerResponse->getData();
        $this->assertIsArray($streamMarkers);
        $this->assertContainsOnlyInstancesOf(Marker::class, $streamMarkers);

        foreach ($streamMarkers as $streamMarker) {
            $this->assertInstanceOf(Marker::class, $streamMarker);
            $this->assertIsString($streamMarker->getId());
            $this->assertIsString($streamMarker->getDescription());
            $this->assertInstanceOf(DateTimeImmutable::class, $streamMarker->getCreatedAt());
            $this->assertNull($streamMarker->getUrl());
            $this->assertIsInt($streamMarker->getPositionSeconds());
        }
    }
}
