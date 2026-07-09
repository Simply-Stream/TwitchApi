<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\AdsApi;
use SimplyStream\TwitchApi\Helix\Api\Ads\Request\GetAdScheduleRequest;
use SimplyStream\TwitchApi\Helix\Api\Ads\Request\SnoozeNextAdRequest;
use SimplyStream\TwitchApi\Helix\Api\Ads\Request\StartCommercialRequest;
use SimplyStream\TwitchApi\Helix\Api\Ads\Response\AdScheduleResponse;
use SimplyStream\TwitchApi\Helix\Api\Ads\Response\SnoozeNextAdResponse;
use SimplyStream\TwitchApi\Helix\Api\Ads\Response\StartCommercialResponse;
use SimplyStream\TwitchApi\Helix\Models\Ads\AdSchedule;
use SimplyStream\TwitchApi\Helix\Models\Ads\Commercial;
use SimplyStream\TwitchApi\Helix\Models\Ads\SnoozeNextAd;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsAdsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(AdsApi::class)]
final class AdsApiTest extends TestCase
{
    use BuildsAdsApi;

    #[Test]
    public function start_commercial_denormalizes_into_the_response_graph(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'length'      => 60,
                'message'     => '',
                'retry_after' => 480,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->startCommercial(
            new StartCommercialRequest(broadcasterId: '1234', length: 60),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(StartCommercialResponse::class, $response);
        $this->assertCount(1, $response->data);
        $this->assertInstanceOf(Commercial::class, $response->data[0]);
        $this->assertSame(60, $response->data[0]->length);
        $this->assertSame(480, $response->data[0]->retryAfter);
    }

    #[Test]
    public function get_ad_schedule_denormalizes_into_the_response_graph(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'next_ad_at'        => '2024-01-01T00:00:00Z',
                'last_ad_at'        => '2024-01-01T00:00:00Z',
                'duration'          => 60,
                'preroll_free_time' => 90,
                'snooze_count'      => 1,
                'snooze_refresh_at' => '2024-01-01T00:00:00Z',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getAdSchedule(
            new GetAdScheduleRequest(broadcasterId: '1234'),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(AdScheduleResponse::class, $response);
        $this->assertCount(1, $response->data);
        $this->assertInstanceOf(AdSchedule::class, $response->data[0]);
        $this->assertInstanceOf(\DateTimeInterface::class, $response->data[0]->nextAdAt);
        $this->assertSame(1, $response->data[0]->snoozeCount);
    }

    #[Test]
    public function snooze_next_ad_denormalizes_into_the_response_graph(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'snooze_count'      => 2,
                'snooze_refresh_at' => '2024-01-01T00:00:00Z',
                'next_ad_at'        => '2024-01-01T00:30:00Z',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->snoozeNextAd(
            new SnoozeNextAdRequest(broadcasterId: '1234'),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(SnoozeNextAdResponse::class, $response);
        $this->assertCount(1, $response->data);
        $this->assertInstanceOf(SnoozeNextAd::class, $response->data[0]);
        $this->assertSame(2, $response->data[0]->snoozeCount);
    }
}
