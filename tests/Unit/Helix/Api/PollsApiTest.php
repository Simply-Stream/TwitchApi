<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Polls\Request\CreatePollRequest;
use SimplyStream\TwitchApi\Helix\Api\Polls\Request\EndPollRequest;
use SimplyStream\TwitchApi\Helix\Api\Polls\Request\GetPollsRequest;
use SimplyStream\TwitchApi\Helix\Api\Polls\Response\PollResponse;
use SimplyStream\TwitchApi\Helix\Api\Polls\Response\PollsResponse;
use SimplyStream\TwitchApi\Helix\Api\PollsApi;
use SimplyStream\TwitchApi\Helix\Models\Polls\CreatePoll;
use SimplyStream\TwitchApi\Helix\Models\Polls\EndPoll;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(PollsApi::class)]
final class PollsApiTest extends TestCase
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

    private function api(): PollsApi
    {
        return new PollsApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function get_polls_omits_empty_ids_and_null_after(): void
    {
        $raw = ['data' => []];
        $expected = new PollsResponse(data: []);

        // ids defaults to [] -> filtered; after null -> filtered; broadcasterId + first remain.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'polls', $this->token, [
                'broadcaster_id' => '1234',
                'first'          => 20,
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, PollsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getPolls(new GetPollsRequest(broadcasterId: '1234'), $this->token),
        );
    }

    #[Test]
    public function get_polls_repeats_ids(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'polls', $this->token, [
                'broadcaster_id' => '1234',
                'id'             => ['p1', 'p2'],
                'first'          => 20,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new PollsResponse(data: []));

        $this->api()->getPolls(
            new GetPollsRequest(broadcasterId: '1234', ids: ['p1', 'p2']),
            $this->token,
        );
    }
    #[Test]
    public function create_poll_posts_normalized_payload(): void
    {
        $poll = new CreatePoll(
            broadcasterId: '1234',
            title: 'Best game?',
            choices: [],
            duration: 60,
        );
        $normalized = ['broadcaster_id' => '1234', 'title' => 'Best game?', 'duration' => 60];
        $raw = ['data' => []];
        $expected = new PollResponse(data: []);

        $this->normalizer->expects($this->once())->method('normalize')->with($poll)->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'polls', $this->token, [], $normalized)
            ->willReturn($raw);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->createPoll(new CreatePollRequest(poll: $poll), $this->token),
        );
    }

    #[Test]
    public function end_poll_patches_normalized_payload(): void
    {
        $poll = new EndPoll(
            broadcasterId: '1234',
            id: 'poll-1',
            status: 'TERMINATED',
        );
        $normalized = ['broadcaster_id' => '1234', 'id' => 'poll-1', 'status' => 'TERMINATED'];
        $raw = ['data' => []];
        $expected = new PollResponse(data: []);

        $this->normalizer->expects($this->once())->method('normalize')->with($poll)->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PATCH', 'polls', $this->token, [], $normalized)
            ->willReturn($raw);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->endPoll(new EndPollRequest(poll: $poll), $this->token),
        );
    }
}
