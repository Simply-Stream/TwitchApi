<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional\Helix\Api;

use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Ads\Request\StartCommercialRequest;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsAdsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

final class AdsApiTest extends TestCase
{
    use BuildsAdsApi;

    #[Test]
    public function start_commercial_sends_a_post_with_the_mapped_body(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [['length' => 60, 'message' => '', 'retry_after' => 480]],
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->startCommercial(
            new StartCommercialRequest(broadcasterId: '1234', length: 60),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertStringEndsWith('/channels/commercial', (string) $request->getUri());

        $sent = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame(['broadcaster_id' => '1234', 'length' => 60], $sent);
    }

    #[Test]
    public function get_ad_schedule_sends_a_get_with_the_broadcaster_id(): void
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

        $this->buildApi($http)->getAdSchedule('1234', new StaticAccessToken());

        $request = $http->getLastRequest();
        $this->assertSame('GET', $request->getMethod());

        $uri = (string) $request->getUri();
        $this->assertStringContainsString('/channels/ads', $uri);
        $this->assertStringContainsString('broadcaster_id=1234', $uri);
    }

    #[Test]
    public function snooze_next_ad_sends_a_post_with_the_broadcaster_id_as_query(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'snooze_count'      => 0,
                'snooze_refresh_at' => '2024-01-01T00:00:00Z',
                'next_ad_at'        => '2024-01-01T00:00:00Z',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->snoozeNextAd('1234', new StaticAccessToken());

        $request = $http->getLastRequest();
        $this->assertSame('POST', $request->getMethod());

        $uri = (string) $request->getUri();
        $this->assertStringContainsString('/channels/ads/schedule/snooze', $uri);
        $this->assertStringContainsString('broadcaster_id=1234', $uri);
    }
}
