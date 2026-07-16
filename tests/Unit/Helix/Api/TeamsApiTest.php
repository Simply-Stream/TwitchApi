<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Teams\Request\GetChannelTeamsRequest;
use SimplyStream\TwitchApi\Helix\Api\Teams\Request\GetTeamsRequest;
use SimplyStream\TwitchApi\Helix\Api\Teams\Response\ChannelTeamsResponse;
use SimplyStream\TwitchApi\Helix\Api\Teams\Response\TeamsResponse;
use SimplyStream\TwitchApi\Helix\Api\TeamsApi;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(TeamsApi::class)]
final class TeamsApiTest extends TestCase
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

    private function api(): TeamsApi
    {
        return new TeamsApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function get_channel_teams_forwards_broadcaster_id(): void
    {
        $raw = ['data' => []];
        $expected = new ChannelTeamsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'teams/channel', $this->token, ['broadcaster_id' => '1234'])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, ChannelTeamsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getChannelTeams(new GetChannelTeamsRequest(broadcasterId: '1234'), $this->token),
        );
    }

    #[Test]
    public function get_teams_forwards_id(): void
    {
        $raw = ['data' => []];
        $expected = new TeamsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'teams', $this->token, ['id' => 'team-1'])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, TeamsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getTeams(new GetTeamsRequest(id: 'team-1'), $this->token),
        );
    }

    #[Test]
    public function get_teams_forwards_name(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'teams', $this->token, ['name' => 'my-team'])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new TeamsResponse(data: []));

        $this->api()->getTeams(new GetTeamsRequest(name: 'my-team'), $this->token);
    }
}
