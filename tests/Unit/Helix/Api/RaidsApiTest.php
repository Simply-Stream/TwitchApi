<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Raids\Request\CancelRaidRequest;
use SimplyStream\TwitchApi\Helix\Api\Raids\Request\StartRaidRequest;
use SimplyStream\TwitchApi\Helix\Api\Raids\Response\RaidResponse;
use SimplyStream\TwitchApi\Helix\Api\RaidsApi;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(RaidsApi::class)]
final class RaidsApiTest extends TestCase
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

    private function api(): RaidsApi
    {
        return new RaidsApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function start_raid_posts_query_only_and_denormalizes(): void
    {
        $raw = ['data' => []];
        $expected = new RaidResponse(data: []);

        $this->normalizer->expects($this->never())->method('normalize');

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'raids', $this->token, [
                'from_broadcaster_id' => 'from-1',
                'to_broadcaster_id'   => 'to-1',
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, RaidResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->startRaid(
                new StartRaidRequest(fromBroadcasterId: 'from-1', toBroadcasterId: 'to-1'),
                $this->token,
            ),
        );
    }

    #[Test]
    public function cancel_raid_deletes_query_without_response(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('DELETE', 'raids', $this->token, ['broadcaster_id' => '1234'])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->cancelRaid(new CancelRaidRequest(broadcasterId: '1234'), $this->token);
    }
}
