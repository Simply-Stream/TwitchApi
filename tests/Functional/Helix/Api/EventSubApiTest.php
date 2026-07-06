<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional\Helix\Api;

use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\EventSub\Request\CreateEventSubSubscriptionRequest;
use SimplyStream\TwitchApi\Helix\Api\EventSub\SubscriptionStatus;
use SimplyStream\TwitchApi\Helix\Api\EventSub\Transport;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsEventSubApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

final class EventSubApiTest extends TestCase
{
    use BuildsEventSubApi;

    #[Test]
    public function create_sends_the_request_and_returns_the_created_subscription(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(202, [], json_encode([
            'data' => [[
                'id'         => 'sub-created',
                'status'     => 'webhook_callback_verification_pending',
                'type'       => 'stream.online',
                'version'    => '1',
                'condition'  => ['broadcaster_user_id' => '5678'],
                'transport'  => ['method' => 'webhook', 'callback' => 'https://example.com/webhook'],
                'created_at' => '2024-01-01T00:00:00Z',
                'cost'       => 1,
            ]],
            'total'          => 1,
            'total_cost'     => 1,
            'max_total_cost' => 10000,
        ], JSON_THROW_ON_ERROR)));

        $api = $this->buildApi($http);

        $response = $api->createEventSubSubscription(
            new CreateEventSubSubscriptionRequest(
                type: 'stream.online',
                version: '1',
                condition: ['broadcaster_user_id' => '5678'],
                transport: new Transport(method: 'webhook', callback: 'https://example.com/webhook'),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertStringEndsWith('/eventsub/subscriptions', (string) $request->getUri());

        $sent = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('stream.online', $sent['type']);
        $this->assertSame(['method' => 'webhook', 'callback' => 'https://example.com/webhook'], $sent['transport']);

        $created = $response->data[0];
        $this->assertSame('sub-created', $created->id);
        $this->assertSame(SubscriptionStatus::WebhookCallbackVerificationPending, $created->status);
    }

    #[Test]
    public function delete_issues_a_delete_with_the_id(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->deleteEventSubSubscription('sub-1', new StaticAccessToken());

        $request = $http->getLastRequest();
        $this->assertSame('DELETE', $request->getMethod());
        $this->assertStringContainsString('id=sub-1', (string) $request->getUri());
    }

    #[Test]
    #[DataProvider('subscriptionProvider')]
    public function get_keeps_condition_as_array_across_types(
        string $type,
        array $condition,
    ): void {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'         => 'sub-1',
                'status'     => 'enabled',
                'type'       => $type,
                'version'    => '1',
                'condition'  => $condition,
                'transport'  => ['method' => 'webhook', 'callback' => 'https://example.com/webhook'],
                'created_at' => '2024-01-01T00:00:00Z',
                'cost'       => 1,
            ]],
            'total'          => 1,
            'total_cost'     => 1,
            'max_total_cost' => 10000,
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getEventSubSubscriptions(
            new StaticAccessToken(),
            status: SubscriptionStatus::Enabled,
        );

        $subscription = $response->data[0];
        $this->assertSame($type, $subscription->type);
        $this->assertSame($condition, $subscription->condition);
        $this->assertStringContainsString('status=enabled', (string) $http->getLastRequest()->getUri());
    }

    /** @return iterable<string, array{string, array<string, mixed>}> */
    public static function subscriptionProvider(): iterable
    {
        yield 'user.update' => ['user.update', ['user_id' => '1234']];
        yield 'stream.online' => ['stream.online', ['broadcaster_user_id' => '5678']];
        yield 'channel.follow' => ['channel.follow', ['broadcaster_user_id' => '5678', 'moderator_user_id' => '5678']];
    }
}
