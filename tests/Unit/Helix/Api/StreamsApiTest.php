<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Streams\Request\CreateStreamMarkerRequest;
use SimplyStream\TwitchApi\Helix\Api\Streams\Request\GetFollowedStreamsRequest;
use SimplyStream\TwitchApi\Helix\Api\Streams\Request\GetStreamKeyRequest;
use SimplyStream\TwitchApi\Helix\Api\Streams\Request\GetStreamMarkersRequest;
use SimplyStream\TwitchApi\Helix\Api\Streams\Request\GetStreamsRequest;
use SimplyStream\TwitchApi\Helix\Api\Streams\Response\CreateStreamMarkerResponse;
use SimplyStream\TwitchApi\Helix\Api\Streams\Response\StreamKeyResponse;
use SimplyStream\TwitchApi\Helix\Api\Streams\Response\StreamMarkersResponse;
use SimplyStream\TwitchApi\Helix\Api\Streams\Response\StreamsResponse;
use SimplyStream\TwitchApi\Helix\Api\Streams\StreamType;
use SimplyStream\TwitchApi\Helix\Api\StreamsApi;
use SimplyStream\TwitchApi\Helix\Models\Streams\CreateStreamMarker;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(StreamsApi::class)]
final class StreamsApiTest extends TestCase
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

    private function api(): StreamsApi
    {
        return new StreamsApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function get_stream_key_forwards_broadcaster_id(): void
    {
        $raw = ['data' => []];
        $expected = new StreamKeyResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'streams/key', $this->token, ['broadcaster_id' => '1234'])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, StreamKeyResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getStreamKey(new GetStreamKeyRequest(broadcasterId: '1234'), $this->token),
        );
    }

    #[Test]
    public function get_streams_unwraps_type_enum_and_omits_empty_lists(): void
    {
        $raw = ['data' => []];
        $expected = new StreamsResponse(data: []);

        // userIds/userLogins/gameIds/languages default to [] -> filtered; type default enum always present.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'streams', $this->token, [
                'type'  => 'all',
                'first' => 20,
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, StreamsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getStreams(new GetStreamsRequest(), $this->token),
        );
    }

    #[Test]
    public function get_streams_repeats_all_id_lists_and_forwards_live_type(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'streams', $this->token, [
                'user_id'    => ['u1'],
                'user_login' => ['login1'],
                'game_id'    => ['g1'],
                'type'       => 'live',
                'language'   => ['de'],
                'first'      => 50,
                'before'     => 'before-cursor',
                'after'      => 'after-cursor',
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new StreamsResponse(data: []));

        $this->api()->getStreams(
            new GetStreamsRequest(
                userIds: ['u1'],
                userLogins: ['login1'],
                gameIds: ['g1'],
                type: StreamType::Live,
                languages: ['de'],
                first: 50,
                before: 'before-cursor',
                after: 'after-cursor',
            ),
            $this->token,
        );
    }

    #[Test]
    public function get_followed_streams_omits_null_after(): void
    {
        $raw = ['data' => []];
        $expected = new StreamsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'streams/followed', $this->token, [
                'user_id' => 'user-1',
                'first'   => 100,
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, StreamsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getFollowedStreams(new GetFollowedStreamsRequest(userId: 'user-1'), $this->token),
        );
    }

    #[Test]
    public function create_stream_marker_posts_normalized_payload_without_query(): void
    {
        $marker = new CreateStreamMarker(userId: 'user-1', description: 'Highlight');
        $normalized = ['user_id' => 'user-1', 'description' => 'Highlight'];
        $raw = ['data' => []];
        $expected = new CreateStreamMarkerResponse(data: []);

        $this->normalizer->expects($this->once())->method('normalize')->with($marker)->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'streams/markers', $this->token, [], $normalized)
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, CreateStreamMarkerResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->createStreamMarker(
                new CreateStreamMarkerRequest(marker: $marker),
                $this->token,
            ),
        );
    }

    #[Test]
    public function get_stream_markers_omits_null_video_id(): void
    {
        $raw = ['data' => []];
        $expected = new StreamMarkersResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'streams/markers', $this->token, [
                'user_id' => 'user-1',
                'first'   => 20,
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, StreamMarkersResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getStreamMarkers(new GetStreamMarkersRequest(userId: 'user-1'), $this->token),
        );
    }
}
