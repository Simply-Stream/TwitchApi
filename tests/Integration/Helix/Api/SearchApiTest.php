<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Search\Request\SearchCategoriesRequest;
use SimplyStream\TwitchApi\Helix\Api\Search\Request\SearchChannelsRequest;
use SimplyStream\TwitchApi\Helix\Api\Search\Response\SearchCategoriesResponse;
use SimplyStream\TwitchApi\Helix\Api\Search\Response\SearchChannelsResponse;
use SimplyStream\TwitchApi\Helix\Api\SearchApi;
use SimplyStream\TwitchApi\Helix\Models\Search\Category;
use SimplyStream\TwitchApi\Helix\Models\Search\Channel;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsSearchApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(SearchApi::class)]
final class SearchApiTest extends TestCase
{
    use BuildsSearchApi;

    #[Test]
    public function search_categories_denormalizes_the_category_list(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'          => '33214',
                'name'        => 'Fortnite',
                'box_art_url' => 'https://static-cdn.jtvnw.net/ttv-boxart/33214-52x72.jpg',
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->searchCategories(
            new SearchCategoriesRequest(query: 'fort'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/search/categories', $request->getUri()->getPath());
        $this->assertSame('query=fort&first=20', $request->getUri()->getQuery());

        $this->assertInstanceOf(SearchCategoriesResponse::class, $response);
        $this->assertSame('cursor-1', $response->pagination?->cursor);

        $category = $response->data[0];
        $this->assertInstanceOf(Category::class, $category);
        $this->assertSame('Fortnite', $category->name);
        $this->assertSame('33214', $category->id);
    }

    #[Test]
    public function search_categories_url_encodes_a_multi_word_query(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->searchCategories(
            new SearchCategoriesRequest(query: 'love computer', first: 50, after: 'cursor-1'),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'query=love%20computer&first=50&after=cursor-1',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function search_channels_denormalizes_a_live_channel(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_language' => 'en',
                'broadcaster_login'    => 'a_seagull',
                'display_name'         => 'A_Seagull',
                'game_id'              => '506442',
                'game_name'            => 'DOOM Eternal',
                'id'                   => '19070311',
                'is_live'              => true,
                'tags'                 => ['English'],
                'thumbnail_url'        => 'https://static-cdn.jtvnw.net/jtv_user_pictures/a_seagull-profile_image-4d2d235688c7dc66-300x300.png',
                'title'                => 'a_seagull',
                'started_at'           => '2020-03-18T17:56:00Z',
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->searchChannels(
            new SearchChannelsRequest(query: 'a_seagull'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/search/channels', $request->getUri()->getPath());

        // live_only defaults to false and must survive as a literal.
        $this->assertSame('query=a_seagull&live_only=false&first=20', $request->getUri()->getQuery());

        $this->assertInstanceOf(SearchChannelsResponse::class, $response);

        $channel = $response->data[0];
        $this->assertInstanceOf(Channel::class, $channel);
        $this->assertSame('A_Seagull', $channel->displayName);
        $this->assertTrue($channel->isLive);
        $this->assertSame(['English'], $channel->tags);
        $this->assertSame('DOOM Eternal', $channel->gameName);
        $this->assertInstanceOf(DateTimeInterface::class, $channel->startedAt);
    }

    #[Test]
    public function search_channels_handles_an_offline_channel(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_language' => '',
                'broadcaster_login'    => 'offlinestreamer',
                'display_name'         => 'OfflineStreamer',
                'game_id'              => '',
                'game_name'            => '',
                'id'                   => '12345',
                'is_live'              => false,
                'tags'                 => [],
                'thumbnail_url'        => 'https://static-cdn.jtvnw.net/user-default-pictures/300x300.png',
                'title'                => '',
                // Twitch sends an empty string, not null, when the channel is offline.
                'started_at'           => '',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->searchChannels(
            new SearchChannelsRequest(query: 'offlinestreamer'),
            new StaticAccessToken(),
        );

        $channel = $response->data[0];
        $this->assertFalse($channel->isLive);
        $this->assertNull($channel->startedAt);
        $this->assertSame('', $channel->gameId);
        $this->assertSame([], $channel->tags);
    }

    #[Test]
    public function search_channels_forwards_live_only_true(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->searchChannels(
            new SearchChannelsRequest(query: 'chess', liveOnly: true),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'query=chess&live_only=true&first=20',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }
}
