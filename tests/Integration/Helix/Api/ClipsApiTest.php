<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeImmutable;
use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Clips\Request\CreateClipRequest;
use SimplyStream\TwitchApi\Helix\Api\Clips\Request\GetClipsRequest;
use SimplyStream\TwitchApi\Helix\Api\Clips\Response\ClipsResponse;
use SimplyStream\TwitchApi\Helix\Api\Clips\Response\CreateClipResponse;
use SimplyStream\TwitchApi\Helix\Api\ClipsApi;
use SimplyStream\TwitchApi\Helix\Models\Clip\Clip;
use SimplyStream\TwitchApi\Helix\Models\Clip\ClipProcess;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsClipsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(ClipsApi::class)]
final class ClipsApiTest extends TestCase
{
    use BuildsClipsApi;

    #[Test]
    public function create_clip_sends_a_boolean_query_literal(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(202, [], json_encode([
            'data' => [[
                'edit_url' => 'http://clips.twitch.tv/AwkwardHelplessSalamanderSwiftRage/edit',
                'id'       => 'AwkwardHelplessSalamanderSwiftRage',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->createClip(
            new CreateClipRequest(broadcasterId: '44322889', hasDelay: true),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/helix/clips', $request->getUri()->getPath());

        // (string) true would be "1" — Twitch needs the literal.
        $this->assertSame('broadcaster_id=44322889&has_delay=true', $request->getUri()->getQuery());

        $this->assertInstanceOf(CreateClipResponse::class, $response);
        $clip = $response->data[0];
        $this->assertInstanceOf(ClipProcess::class, $clip);
        $this->assertSame('AwkwardHelplessSalamanderSwiftRage', $clip->id);
        $this->assertStringEndsWith('/edit', $clip->editUrl);
    }

    #[Test]
    public function create_clip_sends_has_delay_false_by_default(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(202, [], json_encode([
            'data' => [['edit_url' => 'http://example.com/edit', 'id' => 'clip-1']],
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->createClip(
            new CreateClipRequest(broadcasterId: '44322889'),
            new StaticAccessToken(),
        );

        // false must survive as a literal, not vanish or become "".
        $this->assertSame('broadcaster_id=44322889&has_delay=false', $http->getLastRequest()->getUri()->getQuery());
    }

    #[Test]
    public function get_clips_denormalizes_the_full_clip(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'               => 'AwkwardHelplessSalamanderSwiftRage',
                'url'              => 'https://clips.twitch.tv/AwkwardHelplessSalamanderSwiftRage',
                'embed_url'        => 'https://clips.twitch.tv/embed?clip=AwkwardHelplessSalamanderSwiftRage',
                'broadcaster_id'   => '67955580',
                'broadcaster_name' => 'ChewieMelodies',
                'creator_id'       => '53834192',
                'creator_name'     => 'BlackNova03',
                'video_id'         => '205586603',
                'game_id'          => '488191',
                'language'         => 'en',
                'title'            => 'babymetal',
                'view_count'       => 10,
                'created_at'       => '2017-11-30T22:34:18Z',
                'thumbnail_url'    => 'https://clips-media-assets.twitch.tv/157589949-preview-480x272.jpg',
                'duration'         => 60.5,
                'vod_offset'       => 480,
                'is_featured'      => false,
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getClips(
            new GetClipsRequest(broadcasterId: '67955580'),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(ClipsResponse::class, $response);
        $this->assertSame('cursor-1', $response->pagination?->cursor);

        $clip = $response->data[0];
        $this->assertInstanceOf(Clip::class, $clip);
        $this->assertSame('babymetal', $clip->title);
        $this->assertSame(10, $clip->viewCount);
        $this->assertSame(60.5, $clip->duration);
        $this->assertSame(480, $clip->vodOffset);
        $this->assertFalse($clip->isFeatured);
        $this->assertInstanceOf(DateTimeInterface::class, $clip->createdAt);
        $this->assertSame('2017-11-30', $clip->createdAt->format('Y-m-d'));
    }

    #[Test]
    public function get_clips_accepts_a_null_vod_offset(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'               => 'clip-1',
                'url'              => 'https://clips.twitch.tv/clip-1',
                'embed_url'        => 'https://clips.twitch.tv/embed?clip=clip-1',
                'broadcaster_id'   => '67955580',
                'broadcaster_name' => 'ChewieMelodies',
                'creator_id'       => '53834192',
                'creator_name'     => 'BlackNova03',
                'video_id'         => '',
                'game_id'          => '488191',
                'language'         => 'en',
                'title'            => 'no vod yet',
                'view_count'       => 0,
                'created_at'       => '2017-11-30T22:34:18Z',
                'thumbnail_url'    => 'https://example.com/preview.jpg',
                'duration'         => 30.5,
                'vod_offset'       => null,
                'is_featured'      => true,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getClips(
            new GetClipsRequest(broadcasterId: '67955580'),
            new StaticAccessToken(),
        );

        $clip = $response->data[0];
        $this->assertNull($clip->vodOffset);
        $this->assertSame('', $clip->videoId);
        $this->assertTrue($clip->isFeatured);
        $this->assertNull($response->pagination);
    }

    #[Test]
    public function get_clips_repeats_ids_and_formats_the_date_window(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $startedAt = new DateTimeImmutable('2024-01-01T00:00:00+00:00');
        $endedAt = new DateTimeImmutable('2024-01-31T00:00:00+00:00');

        $this->buildApi($http)->getClips(
            new GetClipsRequest(
                ids: ['clip-1', 'clip-2'],
                startedAt: $startedAt,
                endedAt: $endedAt,
                isFeatured: true,
            ),
            new StaticAccessToken(),
        );

        parse_str($http->getLastRequest()->getUri()->getQuery(), $query);

        $this->assertStringContainsString('id=clip-1&id=clip-2', $http->getLastRequest()->getUri()->getQuery());
        $this->assertSame($startedAt->format(DATE_RFC3339), $query['started_at']);
        $this->assertSame($endedAt->format(DATE_RFC3339), $query['ended_at']);
        $this->assertSame('true', $query['is_featured']);
        $this->assertArrayNotHasKey('broadcaster_id', $query);
        $this->assertArrayNotHasKey('game_id', $query);
    }

    #[Test]
    public function get_clips_omits_a_null_is_featured(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getClips(
            new GetClipsRequest(gameId: '488191'),
            new StaticAccessToken(),
        );

        $this->assertSame('game_id=488191&first=20', $http->getLastRequest()->getUri()->getQuery());
    }
}
