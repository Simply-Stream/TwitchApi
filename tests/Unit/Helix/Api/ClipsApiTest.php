<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Clips\Request\CreateClipRequest;
use SimplyStream\TwitchApi\Helix\Api\Clips\Request\GetClipsRequest;
use SimplyStream\TwitchApi\Helix\Api\Clips\Response\ClipsResponse;
use SimplyStream\TwitchApi\Helix\Api\Clips\Response\CreateClipResponse;
use SimplyStream\TwitchApi\Helix\Api\ClipsApi;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(ClipsApi::class)]
final class ClipsApiTest extends TestCase
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

    private function api(): ClipsApi
    {
        return new ClipsApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function create_clip_posts_query_only_with_boolean_delay(): void
    {
        $raw = ['data' => []];
        $expected = new CreateClipResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'clips', $this->token, [
                'broadcaster_id' => '1234',
                'has_delay'      => false,
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, CreateClipResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->createClip(new CreateClipRequest(broadcasterId: '1234'), $this->token),
        );
    }

    #[Test]
    public function create_clip_forwards_has_delay_true(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'clips', $this->token, [
                'broadcaster_id' => '1234',
                'has_delay'      => true,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new CreateClipResponse(data: []));

        $this->api()->createClip(new CreateClipRequest(broadcasterId: '1234', hasDelay: true), $this->token);
    }

    #[Test]
    public function get_clips_forwards_broadcaster_id_and_omits_empty_and_null(): void
    {
        $raw = ['data' => []];
        $expected = new ClipsResponse(data: []);

        // ids defaults to [] -> filtered; all optional filters null -> filtered; first stays.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'clips', $this->token, [
                'broadcaster_id' => '1234',
                'first'          => 20,
            ])
            ->willReturn($raw);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getClips(new GetClipsRequest(broadcasterId: '1234'), $this->token),
        );
    }

    #[Test]
    public function get_clips_repeats_ids(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'clips', $this->token, [
                'id'    => ['c1', 'c2'],
                'first' => 20,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new ClipsResponse(data: []));

        $this->api()->getClips(new GetClipsRequest(ids: ['c1', 'c2']), $this->token);
    }

    #[Test]
    public function get_clips_formats_dates_and_forwards_is_featured(): void
    {
        $startedAt = new \DateTimeImmutable('2024-01-01T00:00:00+00:00');
        $endedAt = new \DateTimeImmutable('2024-01-08T00:00:00+00:00');

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'clips', $this->token, [
                'broadcaster_id' => '1234',
                'started_at'     => $startedAt->format(DATE_RFC3339),
                'ended_at'       => $endedAt->format(DATE_RFC3339),
                'first'          => 50,
                'is_featured'    => true,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new ClipsResponse(data: []));

        $this->api()->getClips(
            new GetClipsRequest(
                broadcasterId: '1234',
                startedAt: $startedAt,
                endedAt: $endedAt,
                first: 50,
                isFeatured: true,
            ),
            $this->token,
        );
    }

    #[Test]
    public function get_clips_omits_is_featured_when_null(): void
    {
        // isFeatured defaults to null (tri-state) -> filtered out entirely, neither true nor false sent.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'clips', $this->token, [
                'broadcaster_id' => '1234',
                'first'          => 20,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new ClipsResponse(data: []));

        $this->api()->getClips(new GetClipsRequest(broadcasterId: '1234'), $this->token);
    }
}
