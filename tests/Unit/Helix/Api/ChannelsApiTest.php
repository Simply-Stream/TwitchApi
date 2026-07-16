<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Channels\Request\GetChannelEditorsRequest;
use SimplyStream\TwitchApi\Helix\Api\Channels\Request\GetChannelFollowersRequest;
use SimplyStream\TwitchApi\Helix\Api\Channels\Request\GetChannelInformationRequest;
use SimplyStream\TwitchApi\Helix\Api\Channels\Request\GetFollowedChannelsRequest;
use SimplyStream\TwitchApi\Helix\Api\Channels\Request\ModifyChannelInformationRequest;
use SimplyStream\TwitchApi\Helix\Api\Channels\Response\ChannelEditorsResponse;
use SimplyStream\TwitchApi\Helix\Api\Channels\Response\ChannelFollowersResponse;
use SimplyStream\TwitchApi\Helix\Api\Channels\Response\ChannelInformationResponse;
use SimplyStream\TwitchApi\Helix\Api\Channels\Response\FollowedChannelsResponse;
use SimplyStream\TwitchApi\Helix\Api\ChannelsApi;
use SimplyStream\TwitchApi\Helix\Models\Channels\ModifyChannelInformation;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(ChannelsApi::class)]
final class ChannelsApiTest extends TestCase
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

    private function api(): ChannelsApi
    {
        return new ChannelsApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function get_channel_information_forwards_broadcaster_ids_as_list(): void
    {
        $raw = ['data' => []];
        $expected = new ChannelInformationResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'channels', $this->token, ['broadcaster_id' => ['1', '2']])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, ChannelInformationResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getChannelInformation(
                new GetChannelInformationRequest(broadcasterIds: ['1', '2']),
                $this->token,
            ),
        );
    }

    #[Test]
    public function modify_channel_information_patches_normalized_body_without_response(): void
    {
        $information = new ModifyChannelInformation();
        $normalized = ['title' => 'New title', 'game_id' => '42'];

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($information)
            ->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PATCH', 'channels', $this->token, ['broadcaster_id' => '1234'], $normalized)
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->modifyChannelInformation(
            new ModifyChannelInformationRequest(broadcasterId: '1234', information: $information),
            $this->token,
        );
    }

    #[Test]
    public function get_channel_editors_forwards_broadcaster_id(): void
    {
        $raw = ['data' => []];
        $expected = new ChannelEditorsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'channels/editors', $this->token, ['broadcaster_id' => '1234'])
            ->willReturn($raw);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getChannelEditors(new GetChannelEditorsRequest(broadcasterId: '1234'), $this->token),
        );
    }

    #[Test]
    public function get_followed_channels_omits_null_and_hits_followed_path(): void
    {
        $expected = new FollowedChannelsResponse(data: [], total: 0);

        // broadcasterId and after default to null -> filtered; userId and first remain.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'channels/followed', $this->token, [
                'user_id' => 'user-1',
                'first'   => 20,
            ])
            ->willReturn(['data' => [], 'total' => 0]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getFollowedChannels(new GetFollowedChannelsRequest(userId: 'user-1'), $this->token),
        );
    }

    #[Test]
    public function get_channel_followers_omits_null_and_hits_followers_path(): void
    {
        $expected = new ChannelFollowersResponse(data: [], total: 0);

        // userId and after default to null -> filtered; broadcasterId and first remain.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'channels/followers', $this->token, [
                'broadcaster_id' => '1234',
                'first'          => 20,
            ])
            ->willReturn(['data' => [], 'total' => 0]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getChannelFollowers(new GetChannelFollowersRequest(broadcasterId: '1234'), $this->token),
        );
    }
}
