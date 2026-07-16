<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Search\Request\SearchCategoriesRequest;
use SimplyStream\TwitchApi\Helix\Api\Search\Request\SearchChannelsRequest;
use SimplyStream\TwitchApi\Helix\Api\Search\Response\SearchCategoriesResponse;
use SimplyStream\TwitchApi\Helix\Api\Search\Response\SearchChannelsResponse;
use SimplyStream\TwitchApi\Helix\Api\SearchApi;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(SearchApi::class)]
final class SearchApiTest extends TestCase
{
    private ApiClientInterface $apiClient;
    private DenormalizerInterface $denormalizer;
    private NormalizerInterface $normalizer;
    private StaticAccessToken $token;

    protected function setUp(): void
    {
        $this->apiClient = $this->createMock(ApiClientInterface::class);
        $this->denormalizer = $this->createMock(DenormalizerInterface::class);
        $this->normalizer = $this->createMock(NormalizerInterface::class);
        $this->token = new StaticAccessToken();
    }

    private function api(): SearchApi
    {
        return new SearchApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function search_categories_forwards_query_and_omits_null_after(): void
    {
        $raw = ['data' => []];
        $expected = new SearchCategoriesResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'search/categories', $this->token, [
                'query' => 'chess',
                'first' => 20,
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, SearchCategoriesResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->searchCategories(new SearchCategoriesRequest(query: 'chess'), $this->token),
        );
    }

    #[Test]
    public function search_categories_forwards_pagination_cursor(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'search/categories', $this->token, [
                'query' => 'chess',
                'first' => 50,
                'after' => 'cursor-1',
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new SearchCategoriesResponse(data: []));

        $this->api()->searchCategories(
            new SearchCategoriesRequest(query: 'chess', first: 50, after: 'cursor-1'),
            $this->token,
        );
    }

    #[Test]
    public function search_channels_keeps_live_only_false_and_omits_null_after(): void
    {
        $raw = ['data' => []];
        $expected = new SearchChannelsResponse(data: []);

        // liveOnly defaults to false, kept because !== null; after null -> filtered.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'search/channels', $this->token, [
                'query'     => 'angel of death',
                'live_only' => false,
                'first'     => 20,
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, SearchChannelsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->searchChannels(new SearchChannelsRequest(query: 'angel of death'), $this->token),
        );
    }

    #[Test]
    public function search_channels_forwards_live_only_true(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'search/channels', $this->token, [
                'query'     => 'chess',
                'live_only' => true,
                'first'     => 20,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new SearchChannelsResponse(data: []));

        $this->api()->searchChannels(
            new SearchChannelsRequest(query: 'chess', liveOnly: true),
            $this->token,
        );
    }
}
