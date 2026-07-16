<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Analytics\Request\GetExtensionAnalyticsRequest;
use SimplyStream\TwitchApi\Helix\Api\Analytics\Request\GetGameAnalyticsRequest;
use SimplyStream\TwitchApi\Helix\Api\Analytics\Response\ExtensionAnalyticsResponse;
use SimplyStream\TwitchApi\Helix\Api\Analytics\Response\GameAnalyticsResponse;
use SimplyStream\TwitchApi\Helix\Api\AnalyticsApi;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(AnalyticsApi::class)]
final class AnalyticsApiTest extends TestCase
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

    private function api(): AnalyticsApi
    {
        return new AnalyticsApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function get_extension_analytics_omits_null_parameters(): void
    {
        $raw = ['data' => []];
        $expected = new ExtensionAnalyticsResponse(data: []);

        // Only first is sent; all optional params default to null and are filtered out.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'analytics/extensions', $this->token, ['first' => 20])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, ExtensionAnalyticsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getExtensionAnalytics(new GetExtensionAnalyticsRequest(), $this->token),
        );
    }

    #[Test]
    public function get_extension_analytics_formats_dates_with_milliseconds(): void
    {
        $started = new \DateTimeImmutable('2021-10-22T00:00:00+00:00');
        $ended = new \DateTimeImmutable('2021-10-27T00:00:00+00:00');

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'analytics/extensions', $this->token, [
                'extension_id' => 'ext-1',
                'type'         => 'overview_v2',
                'started_at'   => $started->format(DATE_RFC3339_EXTENDED),
                'ended_at'     => $ended->format(DATE_RFC3339_EXTENDED),
                'first'        => 50,
                'after'        => 'cursor',
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new ExtensionAnalyticsResponse(data: []));

        $this->api()->getExtensionAnalytics(
            new GetExtensionAnalyticsRequest(
                extensionId: 'ext-1',
                type: 'overview_v2',
                startedAt: $started,
                endedAt: $ended,
                first: 50,
                after: 'cursor',
            ),
            $this->token,
        );
    }

    #[Test]
    public function get_game_analytics_formats_dates_without_milliseconds(): void
    {
        $started = new \DateTimeImmutable('2021-10-22T00:00:00+00:00');
        $ended = new \DateTimeImmutable('2021-10-27T00:00:00+00:00');

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'analytics/games', $this->token, [
                'game_id'    => 'game-1',
                'started_at' => $started->format(DATE_RFC3339),
                'ended_at'   => $ended->format(DATE_RFC3339),
                'first'      => 20,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new GameAnalyticsResponse(data: []));

        $this->api()->getGameAnalytics(
            new GetGameAnalyticsRequest(
                gameId: 'game-1',
                startedAt: $started,
                endedAt: $ended,
            ),
            $this->token,
        );
    }

    #[Test]
    public function get_game_analytics_omits_null_parameters(): void
    {
        $raw = ['data' => []];
        $expected = new GameAnalyticsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'analytics/games', $this->token, ['first' => 20])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, GameAnalyticsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getGameAnalytics(new GetGameAnalyticsRequest(), $this->token),
        );
    }
}
