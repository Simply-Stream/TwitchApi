<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\EventSub\Request\CreateEventSubSubscriptionRequest;
use SimplyStream\TwitchApi\Helix\Api\EventSub\Request\GetEventSubSubscriptionsRequest;
use SimplyStream\TwitchApi\Helix\Api\EventSub\Response\EventSubSubscriptionsResponse;
use SimplyStream\TwitchApi\Helix\Api\EventSub\SubscriptionStatus;
use SimplyStream\TwitchApi\Helix\Api\EventSub\Transport;
use SimplyStream\TwitchApi\Helix\Api\EventSubApi;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(EventSubApi::class)]
final class EventSubApiTest extends TestCase
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

    private function api(): EventSubApi
    {
        return new EventSubApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function create_maps_webhook_transport_and_condition_as_array(): void
    {
        $request = new CreateEventSubSubscriptionRequest(
            type: 'user.update',
            version: '1',
            condition: ['user_id' => '1234'],
            transport: new Transport(method: 'webhook', callback: 'https://example.com/webhook'),
        );

        $raw = ['data' => [], 'total' => 1, 'total_cost' => 1, 'max_total_cost' => 10000];
        $expected = new EventSubSubscriptionsResponse(data: [], total: 1, totalCost: 1, maxTotalCost: 10000);

        // Transport is mapped manually; session_id/conduit_id are null and filtered out.
        $this->normalizer->expects($this->never())->method('normalize');

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'eventsub/subscriptions', $this->token, [], [
                'type'      => 'user.update',
                'version'   => '1',
                'condition' => ['user_id' => '1234'],
                'transport' => [
                    'method'   => 'webhook',
                    'callback' => 'https://example.com/webhook',
                ],
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, EventSubSubscriptionsResponse::class)
            ->willReturn($expected);

        $this->assertSame($expected, $this->api()->createEventSubSubscription($request, $this->token));
    }

    #[Test]
    public function create_maps_websocket_transport(): void
    {
        $request = new CreateEventSubSubscriptionRequest(
            type: 'channel.follow',
            version: '2',
            condition: ['broadcaster_user_id' => '1234', 'moderator_user_id' => '1234'],
            transport: new Transport(method: 'websocket', sessionId: 'session-abc'),
        );

        // callback/conduit_id null -> filtered; only method + session_id remain.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'eventsub/subscriptions', $this->token, [], [
                'type'      => 'channel.follow',
                'version'   => '2',
                'condition' => ['broadcaster_user_id' => '1234', 'moderator_user_id' => '1234'],
                'transport' => [
                    'method'     => 'websocket',
                    'session_id' => 'session-abc',
                ],
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(
            new EventSubSubscriptionsResponse(data: [], total: 0, totalCost: 0, maxTotalCost: 0),
        );

        $this->api()->createEventSubSubscription($request, $this->token);
    }

    #[Test]
    public function delete_sends_id_as_query_without_response(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('DELETE', 'eventsub/subscriptions', $this->token, ['id' => 'sub-1'])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->deleteEventSubSubscription('sub-1', $this->token);
    }

    #[Test]
    public function get_omits_null_filters(): void
    {
        $raw = ['data' => [], 'total' => 0, 'total_cost' => 0, 'max_total_cost' => 10000];
        $expected = new EventSubSubscriptionsResponse(data: [], total: 0, totalCost: 0, maxTotalCost: 10000);

        // All filters default to null -> empty query.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'eventsub/subscriptions', $this->token, [])
            ->willReturn($raw);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getEventSubSubscriptions(new GetEventSubSubscriptionsRequest(), $this->token),
        );
    }

    #[Test]
    public function get_unwraps_status_enum_and_forwards_filters(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'eventsub/subscriptions', $this->token, [
                'status'  => 'enabled',
                'type'    => 'user.update',
                'user_id' => 'user-1',
                'after'   => 'cursor-1',
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(
            new EventSubSubscriptionsResponse(data: [], total: 0, totalCost: 0, maxTotalCost: 0),
        );

        $this->api()->getEventSubSubscriptions(
            new GetEventSubSubscriptionsRequest(
                status: SubscriptionStatus::Enabled,
                type: 'user.update',
                userId: 'user-1',
                after: 'cursor-1',
            ),
            $this->token,
        );
    }
}
