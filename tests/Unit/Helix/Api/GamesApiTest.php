<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Games\Request\GetGamesRequest;
use SimplyStream\TwitchApi\Helix\Api\Games\Request\GetTopGamesRequest;
use SimplyStream\TwitchApi\Helix\Api\Games\Response\GamesResponse;
use SimplyStream\TwitchApi\Helix\Api\Games\Response\TopGamesResponse;
use SimplyStream\TwitchApi\Helix\Api\GamesApi;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(GamesApi::class)]
final class GamesApiTest extends TestCase
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

    private function api(): GamesApi
    {
        return new GamesApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function get_top_games_omits_null_cursors(): void
    {
        $raw = ['data' => []];
        $expected = new TopGamesResponse(data: []);

        // after/before default to null -> filtered; first stays.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'games/top', $this->token, ['first' => 20])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, TopGamesResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getTopGames(new GetTopGamesRequest(), $this->token),
        );
    }

    #[Test]
    public function get_top_games_forwards_cursors(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'games/top', $this->token, [
                'after' => 'cursor-after',
                'first' => 50,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new TopGamesResponse(data: []));

        $this->api()->getTopGames(new GetTopGamesRequest(after: 'cursor-after', first: 50), $this->token);
    }

    #[Test]
    public function get_games_repeats_ids(): void
    {
        $raw = ['data' => []];
        $expected = new GamesResponse(data: []);

        // names/igdbIds default to [] -> filtered; only id remains.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'games', $this->token, ['id' => ['1', '2']])
            ->willReturn($raw);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getGames(new GetGamesRequest(ids: ['1', '2']), $this->token),
        );
    }

    #[Test]
    public function get_games_forwards_all_three_filter_lists(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'games', $this->token, [
                'id'      => ['1'],
                'name'    => ['Chess'],
                'igdb_id' => ['9876'],
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new GamesResponse(data: []));

        $this->api()->getGames(
            new GetGamesRequest(ids: ['1'], names: ['Chess'], igdbIds: ['9876']),
            $this->token,
        );
    }

    #[Test]
    public function get_games_forwards_names_only(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'games', $this->token, ['name' => ['Chess', 'Go']])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new GamesResponse(data: []));

        $this->api()->getGames(new GetGamesRequest(names: ['Chess', 'Go']), $this->token);
    }
}
