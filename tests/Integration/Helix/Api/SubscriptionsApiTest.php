<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Subscriptions\Request\CheckUserSubscriptionRequest;
use SimplyStream\TwitchApi\Helix\Api\Subscriptions\Request\GetBroadcasterSubscriptionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Subscriptions\Response\BroadcasterSubscriptionsResponse;
use SimplyStream\TwitchApi\Helix\Api\Subscriptions\Response\CheckUserSubscriptionResponse;
use SimplyStream\TwitchApi\Helix\Api\SubscriptionsApi;
use SimplyStream\TwitchApi\Helix\Models\Subscriptions\Subscription;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsSubscriptionsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(SubscriptionsApi::class)]
final class SubscriptionsApiTest extends TestCase
{
    use BuildsSubscriptionsApi;

    #[Test]
    public function get_broadcaster_subscriptions_denormalizes_total_and_points(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_id'    => '141981764',
                'broadcaster_login' => 'twitchdev',
                'broadcaster_name'  => 'TwitchDev',
                'gifter_id'         => '12826',
                'gifter_login'      => 'twitch',
                'gifter_name'       => 'Twitch',
                'is_gift'           => true,
                'tier'              => '1000',
                'plan_name'         => 'Channel Subscription (twitchdev)',
                'user_id'           => '527115020',
                'user_name'         => 'twitchgaming',
                'user_login'        => 'twitchgaming',
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
            'total'      => 13,
            'points'     => 13,
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getBroadcasterSubscriptions(
            new GetBroadcasterSubscriptionsRequest(broadcasterId: '141981764'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/subscriptions', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=141981764&first=20', $request->getUri()->getQuery());

        $this->assertInstanceOf(BroadcasterSubscriptionsResponse::class, $response);
        $this->assertSame(13, $response->total);
        $this->assertSame(13, $response->points);
        $this->assertSame('cursor-1', $response->pagination?->cursor);

        $subscription = $response->data[0];
        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertTrue($subscription->isGift);
        $this->assertSame('1000', $subscription->tier);
        $this->assertSame('Twitch', $subscription->gifterName);
        $this->assertSame('Channel Subscription (twitchdev)', $subscription->planName);
    }

    #[Test]
    public function get_broadcaster_subscriptions_handles_a_non_gifted_subscription(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_id'    => '141981764',
                'broadcaster_login' => 'twitchdev',
                'broadcaster_name'  => 'TwitchDev',
                // Twitch sends empty strings, not null, when the sub was not gifted.
                'gifter_id'         => '',
                'gifter_login'      => '',
                'gifter_name'       => '',
                'is_gift'           => false,
                'tier'              => '3000',
                'plan_name'         => 'Channel Subscription (twitchdev)',
                'user_id'           => '527115020',
                'user_name'         => 'twitchgaming',
                'user_login'        => 'twitchgaming',
            ]],
            'total'  => 1,
            'points' => 6,
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getBroadcasterSubscriptions(
            new GetBroadcasterSubscriptionsRequest(broadcasterId: '141981764'),
            new StaticAccessToken(),
        );

        $subscription = $response->data[0];
        $this->assertFalse($subscription->isGift);
        $this->assertSame('', $subscription->gifterId);
        $this->assertSame('', $subscription->gifterName);

        // A tier-3 sub counts six points.
        $this->assertSame(6, $response->points);
        $this->assertNull($response->pagination);
    }

    #[Test]
    public function get_broadcaster_subscriptions_repeats_the_user_ids(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data'   => [],
            'total'  => 0,
            'points' => 0,
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getBroadcasterSubscriptions(
            new GetBroadcasterSubscriptionsRequest(
                broadcasterId: '141981764',
                userIds: ['u1', 'u2'],
                first: 50,
                before: 'before-cursor',
                after: 'after-cursor',
            ),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'broadcaster_id=141981764&user_id=u1&user_id=u2&first=50&before=before-cursor&after=after-cursor',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function check_user_subscription_uses_the_user_path(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_id'    => '149747285',
                'broadcaster_login' => 'twitchpresents',
                'broadcaster_name'  => 'TwitchPresents',
                'is_gift'           => false,
                'tier'              => '1000',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->checkUserSubscription(
            new CheckUserSubscriptionRequest(broadcasterId: '149747285', userId: '141981764'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/subscriptions/user', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=149747285&user_id=141981764', $request->getUri()->getQuery());

        $this->assertInstanceOf(CheckUserSubscriptionResponse::class, $response);

        $subscription = $response->data[0];
        $this->assertSame('TwitchPresents', $subscription->broadcasterName);
        $this->assertFalse($subscription->isGift);

        // This endpoint omits the optional fields entirely.
        $this->assertNull($subscription->gifterId);
        $this->assertNull($subscription->planName);
        $this->assertNull($subscription->userId);
    }
}
