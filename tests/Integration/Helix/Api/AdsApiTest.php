<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Ads\Request\StartCommercialRequest;
use SimplyStream\TwitchApi\Helix\Api\Ads\Response\AdScheduleResponse;
use SimplyStream\TwitchApi\Helix\Api\Ads\Response\StartCommercialResponse;
use SimplyStream\TwitchApi\Helix\Models\Ads\AdSchedule;
use SimplyStream\TwitchApi\Helix\Models\Ads\Commercial;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsAdsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

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

        $response = $this->buildApi($http)->getAdSchedule('1234', new StaticAccessToken());

        $this->assertInstanceOf(AdScheduleResponse::class, $response);
        $this->assertCount(1, $response->data);
        $this->assertInstanceOf(AdSchedule::class, $response->data[0]);
    }
}
