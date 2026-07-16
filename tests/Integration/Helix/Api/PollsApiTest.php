<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Polls\Request\CreatePollRequest;
use SimplyStream\TwitchApi\Helix\Api\Polls\Request\EndPollRequest;
use SimplyStream\TwitchApi\Helix\Api\Polls\Request\GetPollsRequest;
use SimplyStream\TwitchApi\Helix\Api\Polls\Response\PollResponse;
use SimplyStream\TwitchApi\Helix\Api\Polls\Response\PollsResponse;
use SimplyStream\TwitchApi\Helix\Api\PollsApi;
use SimplyStream\TwitchApi\Helix\Models\Polls\Choice;
use SimplyStream\TwitchApi\Helix\Models\Polls\CreatePoll;
use SimplyStream\TwitchApi\Helix\Models\Polls\EndPoll;
use SimplyStream\TwitchApi\Helix\Models\Polls\Poll;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsPollsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(PollsApi::class)]
final class PollsApiTest extends TestCase
{
    use BuildsPollsApi;

    /** @return array<string, mixed> */
    private function pollPayload(string $status = 'ACTIVE', ?string $endedAt = null): array
    {
        return [
            'id'                => 'ed961efd-8a3f-4cf5-a9d0-e616c590cd2a',
            'broadcaster_id'    => '55696719',
            'broadcaster_name'  => 'TwitchDev',
            'broadcaster_login' => 'twitchdev',
            'title'             => 'Heads or Tails?',
            'choices'           => [
                [
                    'id'                   => '4c123012-1351-4f33-84b7-43856e7a0f47',
                    'title'                => 'Heads',
                    'votes'                => 0,
                    'channel_points_votes' => 0,
                    'bits_votes'           => 0,
                ],
                [
                    'id'                   => '279087e3-54a7-467e-bcd0-c1393fcea4f0',
                    'title'                => 'Tails',
                    'votes'                => 0,
                    'channel_points_votes' => 0,
                    'bits_votes'           => 0,
                ],
            ],
            'bits_voting_enabled'           => false,
            'bits_per_vote'                 => 0,
            'channel_points_voting_enabled' => false,
            'channel_points_per_vote'       => 0,
            'status'                        => $status,
            'duration'                      => 1800,
            'started_at'                    => '2021-03-19T06:08:33.871278372Z',
            'ended_at'                      => $endedAt,
        ];
    }

    #[Test]
    public function get_polls_denormalizes_the_choice_list(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data'       => [$this->pollPayload()],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getPolls(
            new GetPollsRequest(broadcasterId: '141981764'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/polls', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=141981764&first=20', $request->getUri()->getQuery());

        $this->assertInstanceOf(PollsResponse::class, $response);
        $this->assertSame('cursor-1', $response->pagination?->cursor);

        $poll = $response->data[0];
        $this->assertInstanceOf(Poll::class, $poll);
        $this->assertSame('Heads or Tails?', $poll->title);
        $this->assertSame('ACTIVE', $poll->status);
        $this->assertSame(1800, $poll->duration);
        $this->assertFalse($poll->bitsVotingEnabled);
        $this->assertFalse($poll->channelPointsVotingEnabled);
        $this->assertInstanceOf(DateTimeInterface::class, $poll->startedAt);

        // An active poll has not ended.
        $this->assertNull($poll->endedAt);

        $this->assertCount(2, $poll->choices);
        $choice = $poll->choices[0];
        $this->assertInstanceOf(Choice::class, $choice);
        $this->assertSame('Heads', $choice->title);
        $this->assertSame(0, $choice->votes);
        $this->assertSame(0, $choice->channelPointsVotes);
    }

    #[Test]
    public function get_polls_repeats_the_ids(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getPolls(
            new GetPollsRequest(broadcasterId: '141981764', ids: ['poll-1', 'poll-2'], first: 5, after: 'cursor-1'),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'broadcaster_id=141981764&id=poll-1&id=poll-2&first=5&after=cursor-1',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function create_poll_sends_the_normalized_body_without_a_query(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [$this->pollPayload()],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->createPoll(
            new CreatePollRequest(
                poll: new CreatePoll(
                    broadcasterId: '141981764',
                    title: 'Heads or Tails?',
                    choices: [['title' => 'Heads'], ['title' => 'Tails']],
                    duration: 1800,
                ),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('', $request->getUri()->getQuery());

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame([
            'broadcaster_id' => '141981764',
            'title'          => 'Heads or Tails?',
            'choices'        => [['title' => 'Heads'], ['title' => 'Tails']],
            'duration'       => 1800,
            'channel_points_voting_enabled' => false,
        ], $body);

        $this->assertInstanceOf(PollResponse::class, $response);
        $this->assertInstanceOf(Poll::class, $response->data[0]);
    }

    #[Test]
    public function create_poll_sends_the_optional_channel_points_fields(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [$this->pollPayload()],
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->createPoll(
            new CreatePollRequest(
                poll: new CreatePoll(
                    broadcasterId: '141981764',
                    title: 'Heads or Tails?',
                    choices: [['title' => 'Heads'], ['title' => 'Tails']],
                    duration: 1800,
                    channelPointsVotingEnabled: true,
                    channelPointsPerVote: 100,
                ),
            ),
            new StaticAccessToken(),
        );

        $body = json_decode((string) $http->getLastRequest()->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertTrue($body['channel_points_voting_enabled']);
        $this->assertSame(100, $body['channel_points_per_vote']);
    }

    #[Test]
    public function end_poll_patches_a_terminated_status(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [$this->pollPayload('TERMINATED', '2021-03-19T06:11:26.746889614Z')],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->endPoll(
            new EndPollRequest(
                poll: new EndPoll(
                    broadcasterId: '141981764',
                    id: 'ed961efd-8a3f-4cf5-a9d0-e616c590cd2a',
                    status: 'TERMINATED',
                ),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('PATCH', $request->getMethod());
        $this->assertSame('', $request->getUri()->getQuery());

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame([
            'broadcaster_id' => '141981764',
            'id'             => 'ed961efd-8a3f-4cf5-a9d0-e616c590cd2a',
            'status'         => 'TERMINATED',
        ], $body);

        $poll = $response->data[0];
        $this->assertSame('TERMINATED', $poll->status);
        $this->assertInstanceOf(DateTimeInterface::class, $poll->endedAt);
    }
}
