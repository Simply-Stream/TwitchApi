<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Videos\Request\DeleteVideosRequest;
use SimplyStream\TwitchApi\Helix\Api\Videos\Request\GetVideosRequest;
use SimplyStream\TwitchApi\Helix\Api\Videos\Response\DeleteVideosResponse;
use SimplyStream\TwitchApi\Helix\Api\Videos\Response\VideosResponse;
use SimplyStream\TwitchApi\Helix\Api\Videos\VideoPeriod;
use SimplyStream\TwitchApi\Helix\Api\Videos\VideoSort;
use SimplyStream\TwitchApi\Helix\Api\Videos\VideoType;
use SimplyStream\TwitchApi\Helix\Api\VideosApi;
use SimplyStream\TwitchApi\Helix\Models\Videos\MutedSegment;
use SimplyStream\TwitchApi\Helix\Models\Videos\Video;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsVideosApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(VideosApi::class)]
final class VideosApiTest extends TestCase
{
    use BuildsVideosApi;

    #[Test]
    public function get_videos_denormalizes_an_archive_with_muted_segments(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'             => '335921245',
                'stream_id'      => '5678',
                'user_id'        => '141981764',
                'user_login'     => 'twitchdev',
                'user_name'      => 'TwitchDev',
                'title'          => 'Twitch Developers 101',
                'description'    => 'Welcome to Twitch development!',
                'created_at'     => '2018-11-14T21:30:18Z',
                'published_at'   => '2018-11-14T22:04:30Z',
                'url'            => 'https://www.twitch.tv/videos/335921245',
                'thumbnail_url'  => 'https://static-cdn.jtvnw.net/cf_vods/d2nvs31859zcd8/twitch/%{width}x%{height}.jpg',
                'viewable'       => 'public',
                'view_count'     => 1863062,
                'language'       => 'en',
                'type'           => 'archive',
                'duration'       => '3m21s',
                'muted_segments' => [
                    ['duration' => 30, 'offset' => 120],
                    ['duration' => 60, 'offset' => 300],
                ],
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getVideos(
            new GetVideosRequest(ids: ['335921245']),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/videos', $request->getUri()->getPath());
        $this->assertSame('id=335921245&period=all&sort=time&type=all&first=20', $request->getUri()->getQuery());

        $this->assertInstanceOf(VideosResponse::class, $response);
        $this->assertSame('cursor-1', $response->pagination?->cursor);

        $video = $response->data[0];
        $this->assertInstanceOf(Video::class, $video);
        $this->assertSame('Twitch Developers 101', $video->title);
        $this->assertSame('archive', $video->type);
        $this->assertSame('3m21s', $video->duration);
        $this->assertSame('public', $video->viewable);
        $this->assertSame(1863062, $video->viewCount);
        $this->assertSame('https://www.twitch.tv/videos/335921245', $video->url);
        $this->assertInstanceOf(DateTimeInterface::class, $video->createdAt);
        $this->assertInstanceOf(DateTimeInterface::class, $video->publishedAt);

        // Archives carry the originating stream id.
        $this->assertSame('5678', $video->streamId);

        $this->assertCount(2, $video->mutedSegments);
        $segment = $video->mutedSegments[0];
        $this->assertInstanceOf(MutedSegment::class, $segment);
        $this->assertSame(30, $segment->duration);
        $this->assertSame(120, $segment->offset);
    }

    #[Test]
    public function get_videos_accepts_a_null_stream_id_for_uploads(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'             => '335921246',
                // Only archives originate from a stream.
                'stream_id'      => null,
                'user_id'        => '141981764',
                'user_login'     => 'twitchdev',
                'user_name'      => 'TwitchDev',
                'title'          => 'An upload',
                'description'    => '',
                'created_at'     => '2018-11-14T21:30:18Z',
                'published_at'   => '2018-11-14T22:04:30Z',
                'url'            => 'https://www.twitch.tv/videos/335921246',
                'thumbnail_url'  => 'https://static-cdn.jtvnw.net/cf_vods/thumb.jpg',
                'viewable'       => 'public',
                'view_count'     => 10,
                'language'       => 'en',
                'type'           => 'upload',
                'duration'       => '1h2m3s',
                'muted_segments' => null,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getVideos(
            new GetVideosRequest(ids: ['335921246']),
            new StaticAccessToken(),
        );

        $video = $response->data[0];
        $this->assertNull($video->streamId);
        $this->assertNull($video->mutedSegments);
        $this->assertSame('upload', $video->type);
        $this->assertNull($response->pagination);
    }

    #[Test]
    public function get_videos_forwards_the_non_default_enums(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getVideos(
            new GetVideosRequest(
                gameId: '33214',
                language: 'de',
                period: VideoPeriod::Week,
                sort: VideoSort::Views,
                type: VideoType::Highlight,
                first: 50,
                after: 'after-cursor',
            ),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'game_id=33214&language=de&period=week&sort=views&type=highlight&first=50&after=after-cursor',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function delete_videos_repeats_ids_and_returns_the_deleted_ids(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => ['1234', '9876'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->deleteVideos(
            new DeleteVideosRequest(ids: ['1234', '9876']),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('DELETE', $request->getMethod());
        $this->assertSame('/helix/videos', $request->getUri()->getPath());
        $this->assertSame('id=1234&id=9876', $request->getUri()->getQuery());

        // Unlike most DELETEs, this one returns a body: a plain list of ids.
        $this->assertInstanceOf(DeleteVideosResponse::class, $response);
        $this->assertSame(['1234', '9876'], $response->data);
    }
}
