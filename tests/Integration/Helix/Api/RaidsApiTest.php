<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Raids\Request\CancelRaidRequest;
use SimplyStream\TwitchApi\Helix\Api\Raids\Request\StartRaidRequest;
use SimplyStream\TwitchApi\Helix\Api\Raids\Response\RaidResponse;
use SimplyStream\TwitchApi\Helix\Api\RaidsApi;
use SimplyStream\TwitchApi\Helix\Models\Raids\Raid;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsRaidsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(RaidsApi::class)]
final class RaidsApiTest extends TestCase
{
    use BuildsRaidsApi;

    #[Test]
    public function start_raid_posts_query_only_and_denormalizes_the_raid(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'created_at' => '2022-02-18T07:20:50.52Z',
                'is_mature'  => false,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->startRaid(
            new StartRaidRequest(fromBroadcasterId: '12345', toBroadcasterId: '98765'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/helix/raids', $request->getUri()->getPath());
        $this->assertSame(
            'from_broadcaster_id=12345&to_broadcaster_id=98765',
            $request->getUri()->getQuery(),
        );
        $this->assertSame('[]', (string) $request->getBody());

        $this->assertInstanceOf(RaidResponse::class, $response);
        $this->assertCount(1, $response->data);

        $raid = $response->data[0];
        $this->assertInstanceOf(Raid::class, $raid);
        $this->assertFalse($raid->isMature);
        $this->assertInstanceOf(DateTimeInterface::class, $raid->createdAt);
        $this->assertSame('2022-02-18', $raid->createdAt->format('Y-m-d'));
    }

    #[Test]
    public function start_raid_denormalizes_a_mature_target(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'created_at' => '2022-02-18T07:20:50.52Z',
                'is_mature'  => true,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->startRaid(
            new StartRaidRequest(fromBroadcasterId: '12345', toBroadcasterId: '98765'),
            new StaticAccessToken(),
        );

        $this->assertTrue($response->data[0]->isMature);
    }

    #[Test]
    public function cancel_raid_sends_a_delete(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->cancelRaid(
            new CancelRaidRequest(broadcasterId: '12345'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('DELETE', $request->getMethod());
        $this->assertSame('/helix/raids', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=12345', $request->getUri()->getQuery());
    }
}
