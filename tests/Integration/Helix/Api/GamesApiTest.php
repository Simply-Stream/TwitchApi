<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Games\Request\GetGamesRequest;
use SimplyStream\TwitchApi\Helix\Api\Games\Request\GetTopGamesRequest;
use SimplyStream\TwitchApi\Helix\Api\Games\Response\GamesResponse;
use SimplyStream\TwitchApi\Helix\Api\Games\Response\TopGamesResponse;
use SimplyStream\TwitchApi\Helix\Api\GamesApi;
use SimplyStream\TwitchApi\Helix\Models\Games\Game;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsGamesApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(GamesApi::class)]
final class GamesApiTest extends TestCase
{
    use BuildsGamesApi;

    #[Test]
    public function get_top_games_denormalizes_the_game_list(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'          => '493057',
                'name'        => "PLAYERUNKNOWN'S BATTLEGROUNDS",
                'box_art_url' => 'https://static-cdn.jtvnw.net/ttv-boxart/493057-{width}x{height}.jpg',
                'igdb_id'     => '27789',
            ]],
            'pagination' => ['cursor' => 'eyJiIjpudWxsLCJhIjp7Ik9mZnNldCI6MjB9fQ'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getTopGames(
            new GetTopGamesRequest(),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/games/top', $request->getUri()->getPath());
        $this->assertSame('first=20', $request->getUri()->getQuery());

        $this->assertInstanceOf(TopGamesResponse::class, $response);
        $this->assertSame('eyJiIjpudWxsLCJhIjp7Ik9mZnNldCI6MjB9fQ', $response->pagination?->cursor);

        $game = $response->data[0];
        $this->assertInstanceOf(Game::class, $game);
        $this->assertSame('493057', $game->id);
        $this->assertSame("PLAYERUNKNOWN'S BATTLEGROUNDS", $game->name);
        $this->assertSame('27789', $game->igdbId);
        $this->assertStringContainsString('{width}x{height}', $game->boxArtUrl);
    }

    #[Test]
    public function get_top_games_forwards_both_pagination_cursors(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getTopGames(
            new GetTopGamesRequest(first: 50, before: 'before-cursor', after: 'after-cursor'),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'after=after-cursor&before=before-cursor&first=50',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function get_games_repeats_all_three_filters(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'          => '33214',
                'name'        => 'Fortnite',
                'box_art_url' => 'https://static-cdn.jtvnw.net/ttv-boxart/33214-{width}x{height}.jpg',
                'igdb_id'     => '1905',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getGames(
            new GetGamesRequest(ids: ['33214'], names: ['Fortnite', 'Minecraft'], igdbIds: ['1905']),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'id=33214&name=Fortnite&name=Minecraft&igdb_id=1905',
            $http->getLastRequest()->getUri()->getQuery(),
        );

        $this->assertInstanceOf(GamesResponse::class, $response);
        $this->assertSame('Fortnite', $response->data[0]->name);
        $this->assertNull($response->pagination);
    }

    #[Test]
    public function get_games_url_encodes_names_with_spaces(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getGames(
            new GetGamesRequest(names: ['Just Chatting']),
            new StaticAccessToken(),
        );

        $this->assertSame('name=Just%20Chatting', $http->getLastRequest()->getUri()->getQuery());
    }

    #[Test]
    public function get_games_omits_the_empty_filters(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getGames(
            new GetGamesRequest(ids: ['33214']),
            new StaticAccessToken(),
        );

        $this->assertSame('id=33214', $http->getLastRequest()->getUri()->getQuery());
    }
}
