<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Subscriptions\Request\CheckUserSubscriptionRequest;
use SimplyStream\TwitchApi\Helix\Api\Subscriptions\Request\GetBroadcasterSubscriptionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Subscriptions\Response\BroadcasterSubscriptionsResponse;
use SimplyStream\TwitchApi\Helix\Api\Subscriptions\Response\CheckUserSubscriptionResponse;
use SimplyStream\TwitchApi\Helix\Api\SubscriptionsApi;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(SubscriptionsApi::class)]
final class SubscriptionsApiTest extends TestCase
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

    private function api(): SubscriptionsApi
    {
        return new SubscriptionsApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function get_broadcaster_subscriptions_omits_empty_ids_and_null_cursors(): void
    {
        $raw = ['data' => [], 'total' => 0, 'points' => 0];
        $expected = new BroadcasterSubscriptionsResponse(data: [], total: 0, points: 0);

        // userIds defaults to [] -> filtered; before/after null -> filtered; broadcasterId + first remain.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'subscriptions', $this->token, [
                'broadcaster_id' => '1234',
                'first'          => 20,
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, BroadcasterSubscriptionsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getBroadcasterSubscriptions(
                new GetBroadcasterSubscriptionsRequest(broadcasterId: '1234'),
                $this->token,
            ),
        );
    }

    #[Test]
    public function get_broadcaster_subscriptions_repeats_ids_and_forwards_cursors(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'subscriptions', $this->token, [
                'broadcaster_id' => '1234',
                'user_id'        => ['u1', 'u2'],
                'first'          => 50,
                'before'         => 'before-cursor',
                'after'          => 'after-cursor',
            ])
            ->willReturn(['data' => [], 'total' => 2, 'points' => 4]);

        $this->denormalizer->method('denormalize')->willReturn(
            new BroadcasterSubscriptionsResponse(data: [], total: 2, points: 4),
        );

        $this->api()->getBroadcasterSubscriptions(
            new GetBroadcasterSubscriptionsRequest(
                broadcasterId: '1234',
                userIds: ['u1', 'u2'],
                first: 50,
                before: 'before-cursor',
                after: 'after-cursor',
            ),
            $this->token,
        );
    }

    #[Test]
    public function check_user_subscription_uses_the_user_path(): void
    {
        $raw = ['data' => []];
        $expected = new CheckUserSubscriptionResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'subscriptions/user', $this->token, [
                'broadcaster_id' => '1234',
                'user_id'        => 'user-1',
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, CheckUserSubscriptionResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->checkUserSubscription(
                new CheckUserSubscriptionRequest(broadcasterId: '1234', userId: 'user-1'),
                $this->token,
            ),
        );
    }
}
