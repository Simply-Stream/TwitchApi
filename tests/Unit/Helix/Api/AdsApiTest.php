<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Ads\Request\GetAdScheduleRequest;
use SimplyStream\TwitchApi\Helix\Api\Ads\Request\SnoozeNextAdRequest;
use SimplyStream\TwitchApi\Helix\Api\Ads\Request\StartCommercialRequest;
use SimplyStream\TwitchApi\Helix\Api\Ads\Response\AdScheduleResponse;
use SimplyStream\TwitchApi\Helix\Api\Ads\Response\SnoozeNextAdResponse;
use SimplyStream\TwitchApi\Helix\Api\Ads\Response\StartCommercialResponse;
use SimplyStream\TwitchApi\Helix\Api\AdsApi;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(AdsApi::class)]
final class AdsApiTest extends TestCase
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

    private function api(): AdsApi
    {
        return new AdsApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function start_commercial_sends_body_and_denormalizes_the_response(): void
    {
        $raw = ['data' => [['length' => 60, 'message' => '', 'retry_after' => 480]]];
        $expected = new StartCommercialResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'channels/commercial', $this->token, [], [
                'broadcaster_id' => '1234',
                'length'         => 60,
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, StartCommercialResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->startCommercial(new StartCommercialRequest(broadcasterId: '1234', length: 60), $this->token),
        );
    }

    #[Test]
    public function get_ad_schedule_sends_broadcaster_id_as_query(): void
    {
        $raw = ['data' => []];
        $expected = new AdScheduleResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'channels/ads', $this->token, ['broadcaster_id' => '1234'])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, AdScheduleResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getAdSchedule(new GetAdScheduleRequest(broadcasterId: '1234'), $this->token),
        );
    }

    #[Test]
    public function snooze_next_ad_posts_broadcaster_id_as_query_with_empty_body(): void
    {
        $raw = ['data' => []];
        $expected = new SnoozeNextAdResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'channels/ads/schedule/snooze', $this->token, ['broadcaster_id' => '1234'], [])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, SnoozeNextAdResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->snoozeNextAd(new SnoozeNextAdRequest(broadcasterId: '1234'), $this->token),
        );
    }
}
