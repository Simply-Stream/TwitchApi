<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use League\OAuth2\Client\Token\AccessToken;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\ClipsApi;
use SimplyStream\TwitchApi\Helix\Models\Clip\Clip;
use SimplyStream\TwitchApi\Helix\Models\Clip\ClipProcess;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;
use SimplyStream\TwitchApi\Tests\Helper\UserAwareFunctionalTestCase;

class ClipsApiTest extends UserAwareFunctionalTestCase
{
    public function testCreateClip()
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

        $clipsApi = new ClipsApi($apiClient);
        $createClipResponse = $clipsApi->createClip(
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['clips:edit']))
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $createClipResponse);

        $this->assertCount(1, $createClipResponse->getData());
        $this->assertContainsOnlyInstancesOf(ClipProcess::class, $createClipResponse->getData());

        foreach ($createClipResponse->getData() as $clipProcess) {
            $this->assertIsString($clipProcess->getId());
            $this->assertNotEmpty($clipProcess->getId());
            $this->assertIsString($clipProcess->getEditUrl());
            $this->assertNotEmpty($clipProcess->getEditUrl());
        }
    }

    public function testGetClips()
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

        $clipsApi = new ClipsApi($apiClient);
        $getClipsResponse = $clipsApi->getClips(
            new AccessToken($this->appAccessToken),
            broadcasterId: $testUser['id']
        );

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $getClipsResponse);
        $this->assertContainsOnlyInstancesOf(Clip::class, $getClipsResponse->getData());

        foreach ($getClipsResponse->getData() as $clip) {
            $this->assertIsString($clip->getId());
            $this->assertNotEmpty($clip->getId());
            $this->assertIsString($clip->getUrl());
            $this->assertNotEmpty($clip->getUrl());
            $this->assertIsString($clip->getEmbedUrl());
            $this->assertNotEmpty($clip->getEmbedUrl());
            $this->assertIsString($clip->getBroadcasterId());
            $this->assertNotEmpty($clip->getBroadcasterId());
            $this->assertIsString($clip->getBroadcasterName());
            $this->assertNotEmpty($clip->getBroadcasterName());
            $this->assertIsString($clip->getCreatorId());
            $this->assertNotEmpty($clip->getCreatorId());
            $this->assertIsString($clip->getCreatorName());
            $this->assertNotEmpty($clip->getCreatorName());
            $this->assertIsString($clip->getVideoId());
            $this->assertIsString($clip->getGameId());
            $this->assertIsString($clip->getLanguage());
            $this->assertNotEmpty($clip->getLanguage());
            $this->assertIsString($clip->getTitle());
            $this->assertNotEmpty($clip->getTitle());
            $this->assertIsString($clip->getThumbnailUrl());
            $this->assertNotEmpty($clip->getThumbnailUrl());
            $this->assertSame(0, $clip->getViewCount());
            $this->assertInstanceOf(\DateTimeImmutable::class, $clip->getCreatedAt());
            $this->assertIsFloat($clip->getDuration());
            $this->assertFalse($clip->isFeatured());
            $this->assertIsInt($clip->getVodOffset());
        }
    }
}
