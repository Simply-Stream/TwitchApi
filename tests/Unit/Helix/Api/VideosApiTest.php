<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Videos\Request\DeleteVideosRequest;
use SimplyStream\TwitchApi\Helix\Api\Videos\Request\GetVideosRequest;
use SimplyStream\TwitchApi\Helix\Api\Videos\Response\DeleteVideosResponse;
use SimplyStream\TwitchApi\Helix\Api\Videos\Response\VideosResponse;
use SimplyStream\TwitchApi\Helix\Api\Videos\VideoPeriod;
use SimplyStream\TwitchApi\Helix\Api\Videos\VideoSort;
use SimplyStream\TwitchApi\Helix\Api\Videos\VideoType;
use SimplyStream\TwitchApi\Helix\Api\VideosApi;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(VideosApi::class)]
final class VideosApiTest extends TestCase
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

    private function api(): VideosApi
    {
        return new VideosApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function get_videos_unwraps_default_enums_by_user_id(): void
    {
        $raw = ['data' => []];
        $expected = new VideosResponse(data: []);

        // ids/gameId/language default to null/[] -> filtered; period/sort/type defaults always present.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'videos', $this->token, [
                'user_id' => 'user-1',
                'period'  => 'all',
                'sort'    => 'time',
                'type'    => 'all',
                'first'   => 20,
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, VideosResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getVideos(new GetVideosRequest(userId: 'user-1'), $this->token),
        );
    }

    #[Test]
    public function get_videos_repeats_ids_and_forwards_non_default_enums(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'videos', $this->token, [
                'id'     => ['v1', 'v2'],
                'period' => 'week',
                'sort'   => 'views',
                'type'   => 'highlight',
                'first'  => 20,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new VideosResponse(data: []));

        $this->api()->getVideos(
            new GetVideosRequest(
                ids: ['v1', 'v2'],
                period: VideoPeriod::Week,
                sort: VideoSort::Views,
                type: VideoType::Highlight,
            ),
            $this->token,
        );
    }

    #[Test]
    public function get_videos_by_game_id_forwards_language(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'videos', $this->token, [
                'game_id'  => 'game-1',
                'language' => 'de',
                'period'   => 'all',
                'sort'     => 'time',
                'type'     => 'all',
                'first'    => 20,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new VideosResponse(data: []));

        $this->api()->getVideos(
            new GetVideosRequest(gameId: 'game-1', language: 'de'),
            $this->token,
        );
    }

    #[Test]
    public function delete_videos_uses_delete_with_response(): void
    {
        $raw = ['data' => ['v1', 'v2']];
        $expected = new DeleteVideosResponse(data: ['v1', 'v2']);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('DELETE', 'videos', $this->token, ['id' => ['v1', 'v2']])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, DeleteVideosResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->deleteVideos(new DeleteVideosRequest(ids: ['v1', 'v2']), $this->token),
        );
    }
}
