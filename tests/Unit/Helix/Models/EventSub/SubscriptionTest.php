<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\EventSub;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ConditionInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

class SubscriptionTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $type = "TypeTest";
        $version = "v1.0";
        $condition = new class () implements ConditionInterface {
        };
        $transport = new Transport('webhook', 'callback', 'secret');
        $id = "TestId";
        $status = "enabled";
        $createdAt = new DateTimeImmutable();

        $subscription = new Subscription($type, $version, $condition, $transport, $id, $status, $createdAt);

        $this->assertEquals($type, $subscription->getType());
        $this->assertEquals($version, $subscription->getVersion());
        $this->assertSame($condition, $subscription->getCondition());
        $this->assertSame($transport, $subscription->getTransport());
        $this->assertEquals($id, $subscription->getId());
        $this->assertEquals($status, $subscription->getStatus());
        $this->assertEquals($createdAt, $subscription->getCreatedAt());
    }

    public function testConstructorWithDefaultValues()
    {
        $type = "TypeTest";
        $version = "v1.0";
        $condition = new class () implements ConditionInterface {
        };
        $transport = new Transport('webhook', 'callback', 'secret');

        $subscription = new Subscription($type, $version, $condition, $transport);

        $this->assertEquals($type, $subscription->getType());
        $this->assertEquals($version, $subscription->getVersion());
        $this->assertSame($condition, $subscription->getCondition());
        $this->assertSame($transport, $subscription->getTransport());
        $this->assertNull($subscription->getId());
        $this->assertNull($subscription->getStatus());
        $this->assertNull($subscription->getCreatedAt());
    }
}
