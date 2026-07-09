<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\EventSub\EventSubSubscription;
use SimplyStream\TwitchApi\Helix\Api\EventSub\Request\CreateEventSubSubscriptionRequest;
use SimplyStream\TwitchApi\Helix\Api\EventSub\Request\DeleteEventSubSubscriptionRequest;
use SimplyStream\TwitchApi\Helix\Api\EventSub\Request\GetEventSubSubscriptionsRequest;
use SimplyStream\TwitchApi\Helix\Api\EventSub\Response\EventSubSubscriptionsResponse;
use SimplyStream\TwitchApi\Helix\Api\EventSub\SubscriptionStatus;
use SimplyStream\TwitchApi\Helix\Api\EventSub\Transport;
use SimplyStream\TwitchApi\Helix\Api\EventSubApi;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsEventSubApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(EventSubApi::class)]
final class EventSubApiTest extends TestCase
{
    use BuildsEventSubApi;

    #[Test]
    public function get_event_sub_subscriptions_denormalizes_the_full_object_graph(): void
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

        $response = $this->buildApi($http)->getEventSubSubscriptions(
            new GetEventSubSubscriptionsRequest(),
            new StaticAccessToken(),
        );

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
        $this->assertSame('user.update', $subscription->type);
        $this->assertSame('1', $subscription->version);
        $this->assertSame(['user_id' => '1234'], $subscription->condition);
        $this->assertSame(1, $subscription->cost);

        $this->assertInstanceOf(DateTimeInterface::class, $subscription->createdAt);
        $this->assertSame('2024-01-01T00:00:00+00:00', $subscription->createdAt->format(DATE_ATOM));

        $this->assertInstanceOf(Transport::class, $subscription->transport);
        $this->assertSame('webhook', $subscription->transport->method);
        $this->assertSame('https://example.com/webhook', $subscription->transport->callback);
        $this->assertNull($subscription->transport->sessionId);
    }

    #[Test]
    public function create_event_sub_subscription_sends_a_filtered_transport_body(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(202, [], json_encode([
            'data' => [[
                'id'         => 'sub-1',
                'status'     => 'webhook_callback_verification_pending',
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
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->createEventSubSubscription(
            new CreateEventSubSubscriptionRequest(
                type: 'user.update',
                version: '1',
                condition: ['user_id' => '1234'],
                transport: new Transport(method: 'webhook', callback: 'https://example.com/webhook'),
            ),
            new StaticAccessToken(),
        );

        $subscription = $response->data[0];
        $this->assertSame(SubscriptionStatus::WebhookCallbackVerificationPending, $subscription->status);
        $this->assertSame('https://example.com/webhook', $subscription->transport->callback);

        $body = json_decode((string) $http->getLastRequest()->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame('user.update', $body['type']);
        $this->assertSame('1', $body['version']);
        $this->assertSame(['user_id' => '1234'], $body['condition']);
        $this->assertSame(['method' => 'webhook', 'callback' => 'https://example.com/webhook'], $body['transport']);

        // Null transport fields must not be sent.
        $this->assertArrayNotHasKey('session_id', $body['transport']);
        $this->assertArrayNotHasKey('conduit_id', $body['transport']);
    }

    #[Test]
    public function delete_event_sub_subscription_sends_the_id_as_query(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->deleteEventSubSubscription('sub-1', new StaticAccessToken());

        $request = $http->getLastRequest();

        $this->assertSame('DELETE', $request->getMethod());
        $this->assertSame('id=sub-1', $request->getUri()->getQuery());
        $this->assertStringEndsWith('/eventsub/subscriptions', $request->getUri()->getPath());
    }

    #[Test]
    public function get_event_sub_subscriptions_unwraps_the_status_enum_into_the_query(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data'           => [],
            'total'          => 0,
            'total_cost'     => 0,
            'max_total_cost' => 10000,
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getEventSubSubscriptions(
            new GetEventSubSubscriptionsRequest(
                status: SubscriptionStatus::Enabled,
                type: 'user.update',
                userId: '1234',
                after: 'cursor-1',
            ),
            new StaticAccessToken(),
        );

        parse_str($http->getLastRequest()->getUri()->getQuery(), $query);

        $this->assertSame([
            'status'  => 'enabled',
            'type'    => 'user.update',
            'user_id' => '1234',
            'after'   => 'cursor-1',
        ], $query);
    }

    #[Test]
    public function create_event_sub_subscription_sends_a_session_id_for_websocket_transports(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(202, [], json_encode([
            'data' => [[
                'id'         => 'sub-1',
                'status'     => 'enabled',
                'type'       => 'user.update',
                'version'    => '1',
                'condition'  => ['user_id' => '1234'],
                'transport'  => [
                    'method'       => 'websocket',
                    'session_id'   => 'session-abc',
                    'connected_at' => '2024-01-01T00:00:00Z',
                ],
                'created_at' => '2024-01-01T00:00:00Z',
                'cost'       => 0,
            ]],
            'total'          => 1,
            'total_cost'     => 0,
            'max_total_cost' => 10000,
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->createEventSubSubscription(
            new CreateEventSubSubscriptionRequest(
                type: 'user.update',
                version: '1',
                condition: ['user_id' => '1234'],
                transport: new Transport(method: 'websocket', sessionId: 'session-abc'),
            ),
            new StaticAccessToken(),
        );

        $transport = $response->data[0]->transport;
        $this->assertSame('websocket', $transport->method);
        $this->assertSame('session-abc', $transport->sessionId);
        $this->assertNull($transport->callback);
        $this->assertInstanceOf(DateTimeInterface::class, $transport->connectedAt);
        $this->assertNull($transport->disconnectedAt);

        $body = json_decode((string) $http->getLastRequest()->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame(['method' => 'websocket', 'session_id' => 'session-abc'], $body['transport']);
    }

    #[Test]
    public function every_request_carries_the_client_id_and_bearer_token(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->deleteEventSubSubscription('sub-1', new StaticAccessToken());

        $request = $http->getLastRequest();
        $this->assertSame('client-id', $request->getHeaderLine('Client-Id'));
        $this->assertStringStartsWith('Bearer ', $request->getHeaderLine('Authorization'));
    }
}
