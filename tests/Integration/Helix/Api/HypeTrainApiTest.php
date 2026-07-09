<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\HypeTrain\Request\GetHypeTrainEventsRequest;
use SimplyStream\TwitchApi\Helix\Api\HypeTrain\Response\HypeTrainEventsResponse;
use SimplyStream\TwitchApi\Helix\Api\HypeTrainApi;
use SimplyStream\TwitchApi\Helix\Models\HypeTrain\Contribution;
use SimplyStream\TwitchApi\Helix\Models\HypeTrain\EventData;
use SimplyStream\TwitchApi\Helix\Models\HypeTrain\HypeTrainEvent;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsHypeTrainApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(HypeTrainApi::class)]
final class HypeTrainApiTest extends TestCase
{
    use BuildsHypeTrainApi;

    #[Test]
    public function get_hype_train_events_denormalizes_the_nested_event_data(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'              => '1b0AsbInCHZW2SQFQkCzqN07Ib2',
                'event_type'      => 'hypetrain.progression',
                'event_timestamp' => '2020-04-24T20:07:24Z',
                'version'         => '1.0',
                'event_data'      => [
                    'broadcaster_id'    => '270954519',
                    'cooldown_end_time' => '2020-04-24T20:13:21.003802269Z',
                    'expires_at'        => '2020-04-24T20:12:21.003802269Z',
                    'goal'              => 1800,
                    'id'                => '70f0c7d8-ff60-4c50-b138-f3a352e50e4c',
                    'last_contribution' => [
                        'total' => 200,
                        'type'  => 'BITS',
                        'user'  => '134247454',
                    ],
                    'level'             => 2,
                    'started_at'        => '2020-04-24T20:05:47.30473127Z',
                    'top_contributions' => [
                        [
                            'total' => 600,
                            'type'  => 'BITS',
                            'user'  => '134247450',
                        ],
                        [
                            'total' => 1000,
                            'type'  => 'SUBS',
                            'user'  => '134247449',
                        ],
                    ],
                    'total'             => 600,
                ],
            ]],
            'pagination' => ['cursor' => 'eyJiIjpudWxsLCJhIjp7Ik9mZnNldCI6MX19'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getHypeTrainEvents(
            new GetHypeTrainEventsRequest(broadcasterId: '270954519'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/hypetrain/events', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=270954519&first=1', $request->getUri()->getQuery());

        $this->assertInstanceOf(HypeTrainEventsResponse::class, $response);
        $this->assertSame('eyJiIjpudWxsLCJhIjp7Ik9mZnNldCI6MX19', $response->pagination?->cursor);

        $event = $response->data[0];
        $this->assertInstanceOf(HypeTrainEvent::class, $event);
        $this->assertSame('1b0AsbInCHZW2SQFQkCzqN07Ib2', $event->id);
        $this->assertSame('hypetrain.progression', $event->eventType);
        $this->assertSame('1.0', $event->version);
        $this->assertInstanceOf(DateTimeInterface::class, $event->eventTimestamp);

        $data = $event->eventData;
        $this->assertInstanceOf(EventData::class, $data);
        $this->assertSame('270954519', $data->broadcasterId);
        $this->assertSame(1800, $data->goal);
        $this->assertSame(2, $data->level);
        $this->assertSame(600, $data->total);
        $this->assertInstanceOf(DateTimeInterface::class, $data->cooldownEndTime);
        $this->assertInstanceOf(DateTimeInterface::class, $data->expiresAt);
        $this->assertInstanceOf(DateTimeInterface::class, $data->startedAt);

        // A single Contribution object.
        $this->assertInstanceOf(Contribution::class, $data->lastContribution);
        $this->assertSame(200, $data->lastContribution->total);
        $this->assertSame('BITS', $data->lastContribution->type);
        $this->assertSame('134247454', $data->lastContribution->user);

        // A list of Contribution objects.
        $this->assertCount(2, $data->topContributions);
        $this->assertInstanceOf(Contribution::class, $data->topContributions[0]);
        $this->assertSame(600, $data->topContributions[0]->total);
        $this->assertSame('SUBS', $data->topContributions[1]->type);
    }

    #[Test]
    public function get_hype_train_events_forwards_the_pagination_cursor(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getHypeTrainEvents(
            new GetHypeTrainEventsRequest(broadcasterId: '270954519', first: 10, after: 'cursor-1'),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'broadcaster_id=270954519&first=10&after=cursor-1',
            $http->getLastRequest()->getUri()->getQuery(),
        );
        $this->assertSame([], $response->data);
        $this->assertNull($response->pagination);
    }
}
