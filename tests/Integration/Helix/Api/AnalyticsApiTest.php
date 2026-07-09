<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeImmutable;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Analytics\Request\GetExtensionAnalyticsRequest;
use SimplyStream\TwitchApi\Helix\Api\Analytics\Request\GetGameAnalyticsRequest;
use SimplyStream\TwitchApi\Helix\Api\Analytics\Response\ExtensionAnalyticsResponse;
use SimplyStream\TwitchApi\Helix\Api\Analytics\Response\GameAnalyticsResponse;
use SimplyStream\TwitchApi\Helix\Api\AnalyticsApi;
use SimplyStream\TwitchApi\Helix\Api\DateRange;
use SimplyStream\TwitchApi\Helix\Models\Analytics\ExtensionAnalytics;
use SimplyStream\TwitchApi\Helix\Models\Analytics\GameAnalytics;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsAnalyticsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(AnalyticsApi::class)]
final class AnalyticsApiTest extends TestCase
{
    use BuildsAnalyticsApi;

    #[Test]
    public function get_extension_analytics_denormalizes_into_the_response_graph(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'extension_id' => 'ext-1',
                'URL'          => 'https://example.com/report.csv',
                'type'         => 'overview_v2',
                'date_range'   => [
                    'started_at' => '2024-01-01T00:00:00Z',
                    'ended_at'   => '2024-01-31T00:00:00Z',
                ],
            ]],
            'pagination' => ['cursor' => 'abc'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getExtensionAnalytics(
            new GetExtensionAnalyticsRequest(extensionId: 'ext-1'),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(ExtensionAnalyticsResponse::class, $response);
        $this->assertSame('abc', $response->pagination?->cursor);
        $this->assertCount(1, $response->data);

        $analytics = $response->data[0];
        $this->assertInstanceOf(ExtensionAnalytics::class, $analytics);
        $this->assertSame('ext-1', $analytics->extensionId);
        $this->assertSame('overview_v2', $analytics->type);
        $this->assertSame('https://example.com/report.csv', $analytics->url);

        $this->assertInstanceOf(DateRange::class, $analytics->dateRange);
        $this->assertSame('2024-01-01', $analytics->dateRange->startedAt->format('Y-m-d'));
        $this->assertSame('2024-01-31', $analytics->dateRange->endedAt->format('Y-m-d'));
    }

    #[Test]
    public function get_extension_analytics_formats_dates_and_filters_nulls(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $startedAt = new DateTimeImmutable('2024-01-01T00:00:00+00:00');
        $endedAt = new DateTimeImmutable('2024-01-31T00:00:00+00:00');

        $this->buildApi($http)->getExtensionAnalytics(
            new GetExtensionAnalyticsRequest(
                extensionId: 'ext-1',
                type: 'overview_v2',
                startedAt: $startedAt,
                endedAt: $endedAt,
            ),
            new StaticAccessToken(),
        );

        parse_str($http->getLastRequest()->getUri()->getQuery(), $query);

        $this->assertSame([
            'extension_id' => 'ext-1',
            'type'         => 'overview_v2',
            'started_at'   => $startedAt->format(DATE_RFC3339_EXTENDED),
            'ended_at'     => $endedAt->format(DATE_RFC3339_EXTENDED),
            'first'        => '20',
        ], $query);
    }

    #[Test]
    public function get_game_analytics_denormalizes_into_the_response_graph(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'game_id'    => 'game-1',
                'URL'        => 'https://example.com/game-report.csv',
                'type'       => 'overview_v2',
                'date_range' => [
                    'started_at' => '2024-01-01T00:00:00Z',
                    'ended_at'   => '2024-01-31T00:00:00Z',
                ],
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getGameAnalytics(
            new GetGameAnalyticsRequest(gameId: 'game-1'),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(GameAnalyticsResponse::class, $response);
        $this->assertNull($response->pagination);
        $this->assertCount(1, $response->data);

        $analytics = $response->data[0];
        $this->assertInstanceOf(GameAnalytics::class, $analytics);
        $this->assertSame('game-1', $analytics->gameId);
        $this->assertSame('https://example.com/game-report.csv', $analytics->url);
        $this->assertInstanceOf(DateRange::class, $analytics->dateRange);
    }

    #[Test]
    public function get_game_analytics_uses_plain_rfc3339_for_dates(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $startedAt = new DateTimeImmutable('2024-01-01T00:00:00+00:00');

        $this->buildApi($http)->getGameAnalytics(
            new GetGameAnalyticsRequest(gameId: 'game-1', startedAt: $startedAt),
            new StaticAccessToken(),
        );

        parse_str($http->getLastRequest()->getUri()->getQuery(), $query);

        $this->assertSame($startedAt->format(DATE_RFC3339), $query['started_at']);
        $this->assertArrayNotHasKey('ended_at', $query);
        $this->assertStringEndsWith('/analytics/games', $http->getLastRequest()->getUri()->getPath());
    }
}
