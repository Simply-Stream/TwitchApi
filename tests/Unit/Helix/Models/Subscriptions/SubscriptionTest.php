<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Subscriptions;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Subscriptions\Subscription;

final class SubscriptionTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $subscription = new Subscription(
            '0001',
            'broadcaster_login_1',
            'broadcaster_name_1',
            true,
            '1000',
            '0002',
            'gifter_login_1',
            'gifter_name_1',
            'plan_name_1',
            '0003',
            'user_name_1',
            'user_login_1'
        );

        $this->assertSame('0001', $subscription->getBroadcasterId());
        $this->assertSame('broadcaster_login_1', $subscription->getBroadcasterLogin());
        $this->assertSame('broadcaster_name_1', $subscription->getBroadcasterName());
        $this->assertSame('0002', $subscription->getGifterId());
        $this->assertSame('gifter_login_1', $subscription->getGifterLogin());
        $this->assertSame('gifter_name_1', $subscription->getGifterName());
        $this->assertSame(true, $subscription->isGift());
        $this->assertSame('1000', $subscription->getTier());
        $this->assertSame('plan_name_1', $subscription->getPlanName());
        $this->assertSame('0003', $subscription->getUserId());
        $this->assertSame('user_name_1', $subscription->getUserName());
        $this->assertSame('user_login_1', $subscription->getUserLogin());
    }
}
