<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\HypeTrain\Request\GetHypeTrainEventsRequest;
use SimplyStream\TwitchApi\Helix\Api\HypeTrain\Response\HypeTrainEventsResponse;
use SimplyStream\TwitchApi\Helix\Api\HypeTrainApi;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(HypeTrainApi::class)]
final class HypeTrainApiTest extends TestCase
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

    private function api(): HypeTrainApi
    {
        return new HypeTrainApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function get_hype_train_events_defaults_first_to_one_and_omits_null_after(): void
    {
        $raw = ['data' => []];
        $expected = new HypeTrainEventsResponse(data: []);

        // first defaults to 1 (not 20); after defaults to null -> filtered.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'hypetrain/events', $this->token, [
                'broadcaster_id' => '1234',
                'first'          => 1,
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, HypeTrainEventsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getHypeTrainEvents(new GetHypeTrainEventsRequest(broadcasterId: '1234'), $this->token),
        );
    }

    #[Test]
    public function get_hype_train_events_forwards_first_and_after(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'hypetrain/events', $this->token, [
                'broadcaster_id' => '1234',
                'first'          => 50,
                'after'          => 'cursor-1',
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new HypeTrainEventsResponse(data: []));

        $this->api()->getHypeTrainEvents(
            new GetHypeTrainEventsRequest(broadcasterId: '1234', first: 50, after: 'cursor-1'),
            $this->token,
        );
    }
}
