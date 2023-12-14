<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use DateTimeImmutable;
use GuzzleHttp\Client;
use League\OAuth2\Client\Token\AccessToken;
use Nyholm\Psr7\Factory\Psr17Factory;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\SearchApi;
use SimplyStream\TwitchApi\Helix\Models\Search\Category;
use SimplyStream\TwitchApi\Helix\Models\Search\Channel;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;

class SearchApiTest extends UserAwareFunctionalTestCase
{
    public function testSearchCategories()
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

        $searchApi = new SearchApi($apiClient);
        $searchCategoriesResponse = $searchApi->searchCategories('Just', accessToken: $accessToken);

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $searchCategoriesResponse);
        $categories = $searchCategoriesResponse->getData();
        $this->assertIsArray($categories);
        $this->assertContainsOnlyInstancesOf(Category::class, $categories);

        foreach ($categories as $category) {
            $this->assertIsString($category->getId());
            $this->assertNotEmpty($category->getId());
            $this->assertStringStartsWith('Just ', $category->getName());
            $this->assertIsString($category->getBoxArtUrl());
            $this->assertNotEmpty($category->getBoxArtUrl());
        }

        $this->assertNull($searchCategoriesResponse->getPagination());
        $this->assertNull($searchCategoriesResponse->getTotal());
    }

    public function testSearchChannels()
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

        $searchApi = new SearchApi($apiClient);
        $searchChannelsResponse = $searchApi->searchChannels('mars', accessToken: $accessToken);

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $searchChannelsResponse);
        $channels = $searchChannelsResponse->getData();
        $this->assertIsArray($channels);
        $this->assertContainsOnlyInstancesOf(Channel::class, $channels);

        foreach ($channels as $channel) {
            $this->assertIsString($channel->getBroadcasterLanguage());
            $this->assertNotEmpty($channel->getBroadcasterLanguage());
            $this->assertIsString($channel->getBroadcasterLogin());
            $this->assertNotEmpty($channel->getBroadcasterLogin());
            $this->assertIsString($channel->getDisplayName());
            $this->assertNotEmpty($channel->getDisplayName());
            $this->assertIsString($channel->getId());
            $this->assertNotEmpty($channel->getId());
            $this->assertIsBool($channel->isLive());
            $this->assertIsArray($channel->getTags());
            $this->assertSame(['English', 'CLI Tag'], $channel->getTags());
            $this->assertIsString($channel->getThumbnailUrl());
            $this->assertNotEmpty($channel->getThumbnailUrl());
            $this->assertIsString($channel->getTitle());
            $this->assertNotEmpty($channel->getTitle());
            $this->assertInstanceOf(DateTimeImmutable::class, $channel->getStartedAt());
        }
    }
}
