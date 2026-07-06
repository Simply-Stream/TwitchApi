<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\EventSub\EventSubSubscription;
use SimplyStream\TwitchApi\Helix\Api\EventSub\Response\EventSubSubscriptionsResponse;
use SimplyStream\TwitchApi\Helix\Api\EventSub\SubscriptionStatus;
use SimplyStream\TwitchApi\Helix\Api\EventSub\Transport;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsEventSubApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

final class EventSubApiTest extends TestCase
{
    use BuildsEventSubApi;

    #[Test]
    public function it_denormalizes_the_full_object_graph(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'         => 'sub-1',
                'status'     => 'enabled',
                'type'       => 'user.update',
                'version'    => '1',
                'condition'  => ['user_id' => '1234'],
                'transport'  => ['method' => 'webhook', 'callback' => 'https://example.com/webhook'],
                'created_at' => '2024-01-01T00:00:00Z',
                'cost'       => 1,
            ]],
            'total'          => 1,
            'total_cost'     => 1,
            'max_total_cost' => 10000,
            'pagination'     => ['cursor' => 'abc'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getEventSubSubscriptions(new StaticAccessToken());

        $this->assertInstanceOf(EventSubSubscriptionsResponse::class, $response);
        $this->assertSame(1, $response->total);
        $this->assertSame(1, $response->totalCost);
        $this->assertSame(10000, $response->maxTotalCost);
        $this->assertSame('abc', $response->pagination?->cursor);

        $this->assertCount(1, $response->data);
        $subscription = $response->data[0];

        $this->assertInstanceOf(EventSubSubscription::class, $subscription);
        $this->assertSame('sub-1', $subscription->id);
        $this->assertSame(SubscriptionStatus::Enabled, $subscription->status);
        $this->assertSame(['user_id' => '1234'], $subscription->condition);
        $this->assertSame('2024-01-01T00:00:00Z', $subscription->createdAt);

        $this->assertInstanceOf(Transport::class, $subscription->transport);
        $this->assertSame('webhook', $subscription->transport->method);
        $this->assertSame('https://example.com/webhook', $subscription->transport->callback);
    }
}
