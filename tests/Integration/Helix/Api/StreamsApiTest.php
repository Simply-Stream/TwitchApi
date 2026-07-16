<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
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
use SimplyStream\TwitchApi\Helix\Models\Streams\Marker;
use SimplyStream\TwitchApi\Helix\Models\Streams\Stream;
use SimplyStream\TwitchApi\Helix\Models\Streams\StreamKey;
use SimplyStream\TwitchApi\Helix\Models\Streams\StreamMarker;
use SimplyStream\TwitchApi\Helix\Models\Streams\Video;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsStreamsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(StreamsApi::class)]
final class StreamsApiTest extends TestCase
{
    use BuildsStreamsApi;

    #[Test]
    public function get_stream_key_denormalizes_the_key(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'stream_key' => 'live_44322889_a34ub37c8ajv98a0',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getStreamKey(
            new GetStreamKeyRequest(broadcasterId: '198704263'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/streams/key', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=198704263', $request->getUri()->getQuery());

        $this->assertInstanceOf(StreamKeyResponse::class, $response);
        $this->assertInstanceOf(StreamKey::class, $response->data[0]);
        $this->assertSame('live_44322889_a34ub37c8ajv98a0', $response->data[0]->streamKey);
    }

    #[Test]
    public function get_streams_denormalizes_a_live_stream(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'            => '123456789',
                'user_id'       => '98765',
                'user_login'    => 'sandysanderman',
                'user_name'     => 'SandySanderman',
                'game_id'       => '494131',
                'game_name'     => 'Little Nightmares',
                'type'          => 'live',
                'title'         => 'hablamos y le damos a Little Nightmares 1',
                'tags'          => ['Español'],
                'viewer_count'  => 78365,
                'started_at'    => '2021-03-10T15:04:21Z',
                'language'      => 'es',
                'thumbnail_url' => 'https://static-cdn.jtvnw.net/previews-ttv/live_user_auronplay-{width}x{height}.jpg',
                'is_mature'     => false,
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getStreams(
            new GetStreamsRequest(),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/streams', $request->getUri()->getPath());

        // Only the enum default and first survive an empty request.
        $this->assertSame('type=all&first=20', $request->getUri()->getQuery());

        $this->assertInstanceOf(StreamsResponse::class, $response);
        $this->assertSame('cursor-1', $response->pagination?->cursor);

        $stream = $response->data[0];
        $this->assertInstanceOf(Stream::class, $stream);
        $this->assertSame('live', $stream->type);
        $this->assertSame(78365, $stream->viewerCount);
        $this->assertSame(['Español'], $stream->tags);
        $this->assertFalse($stream->isMature);
        $this->assertInstanceOf(DateTimeInterface::class, $stream->startedAt);
        $this->assertStringContainsString('{width}x{height}', $stream->thumbnailUrl);
    }

    #[Test]
    public function get_streams_repeats_every_list_filter(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getStreams(
            new GetStreamsRequest(
                userIds: ['u1', 'u2'],
                userLogins: ['login1'],
                gameIds: ['g1'],
                type: StreamType::Live,
                languages: ['de', 'en'],
                first: 50,
                before: 'before-cursor',
                after: 'after-cursor',
            ),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'user_id=u1&user_id=u2&user_login=login1&game_id=g1&type=live&language=de&language=en'
            . '&first=50&before=before-cursor&after=after-cursor',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function get_followed_streams_uses_the_followed_path(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'            => '42170724654',
                'user_id'       => '132954738',
                'user_login'    => 'aws',
                'user_name'     => 'AWS',
                'game_id'       => '417752',
                'game_name'     => 'Talk Shows & Podcasts',
                'type'          => 'live',
                'title'         => 'Welcome to AWS on Twitch!',
                'tags'          => [],
                'viewer_count'  => 20,
                'started_at'    => '2021-03-31T15:00:04Z',
                'language'      => 'en',
                'thumbnail_url' => 'https://static-cdn.jtvnw.net/previews-ttv/live_user_aws-{width}x{height}.jpg',
                'is_mature'     => false,
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getFollowedStreams(
            new GetFollowedStreamsRequest(userId: '141981764'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/streams/followed', $request->getUri()->getPath());
        $this->assertSame('user_id=141981764&first=100', $request->getUri()->getQuery());

        $this->assertInstanceOf(StreamsResponse::class, $response);
        $this->assertSame('AWS', $response->data[0]->userName);
    }

    #[Test]
    public function create_stream_marker_sends_the_normalized_body(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'               => '123',
                'created_at'       => '2018-08-20T20:10:03Z',
                'description'      => 'hello, this is a marker!',
                'position_seconds' => 244,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->createStreamMarker(
            new CreateStreamMarkerRequest(
                marker: new CreateStreamMarker(userId: '123', description: 'hello, this is a marker!'),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/helix/streams/markers', $request->getUri()->getPath());
        $this->assertSame('', $request->getUri()->getQuery());

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame([
            'user_id'     => '123',
            'description' => 'hello, this is a marker!',
        ], $body);

        $this->assertInstanceOf(CreateStreamMarkerResponse::class, $response);
        $marker = $response->data[0];
        $this->assertSame(244, $marker->positionSeconds);
        $this->assertInstanceOf(DateTimeInterface::class, $marker->createdAt);
    }

    #[Test]
    public function create_stream_marker_sends_an_empty_description(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'               => '123',
                'created_at'       => '2018-08-20T20:10:03Z',
                'description'      => '',
                'position_seconds' => 244,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->createStreamMarker(
            new CreateStreamMarkerRequest(marker: new CreateStreamMarker(userId: '123', description: '')),
            new StaticAccessToken(),
        );

        $body = json_decode((string) $http->getLastRequest()->getBody(), true, 512, JSON_THROW_ON_ERROR);

        // An empty string is not null, so it survives SKIP_NULL_VALUES.
        $this->assertSame(['user_id' => '123', 'description' => ''], $body);
    }

    #[Test]
    public function get_stream_markers_denormalizes_the_video_and_marker_lists(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'user_id'    => '123',
                'user_name'  => 'TwitchName',
                'user_login' => 'twitchname',
                'videos'     => [[
                    'video_id' => '456',
                    'markers'  => [
                        [
                            'id'               => '106b8d6243a4f883d25ad75e6cdffdc4',
                            'created_at'       => '2018-08-20T20:10:03Z',
                            'description'      => 'hello, this is a marker!',
                            'position_seconds' => 244,
                            'URL'              => 'https://twitch.tv/videos/456?t=0h4m04s',
                        ],
                        [
                            'id'               => '206b8d6243a4f883d25ad75e6cdffdc4',
                            'created_at'       => '2018-08-21T20:10:03Z',
                            'description'      => 'another marker',
                            'position_seconds' => 300,
                            'URL'              => 'https://twitch.tv/videos/456?t=0h5m00s',
                        ],
                    ],
                ]],
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getStreamMarkers(
            new GetStreamMarkersRequest(userId: '123'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/streams/markers', $request->getUri()->getPath());
        $this->assertSame('user_id=123&first=20', $request->getUri()->getQuery());

        $this->assertInstanceOf(StreamMarkersResponse::class, $response);

        $streamMarker = $response->data[0];
        $this->assertInstanceOf(StreamMarker::class, $streamMarker);
        $this->assertSame('TwitchName', $streamMarker->userName);

        $this->assertCount(1, $streamMarker->videos);
        $video = $streamMarker->videos[0];
        $this->assertInstanceOf(Video::class, $video);
        $this->assertSame('456', $video->videoId);

        $this->assertCount(2, $video->markers);
        $marker = $video->markers[0];
        $this->assertInstanceOf(Marker::class, $marker);
        $this->assertSame(244, $marker->positionSeconds);
        $this->assertSame('https://twitch.tv/videos/456?t=0h4m04s', $marker->url);
    }

    #[Test]
    public function get_stream_markers_omits_a_null_video_id(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getStreamMarkers(
            new GetStreamMarkersRequest(videoId: '456', first: 5),
            new StaticAccessToken(),
        );

        $this->assertSame('video_id=456&first=5', $http->getLastRequest()->getUri()->getQuery());
    }
}
