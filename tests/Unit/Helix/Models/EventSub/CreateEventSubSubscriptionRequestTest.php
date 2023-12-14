<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\EventSub;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ConditionInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\CreateEventSubSubscriptionRequest;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

class CreateEventSubSubscriptionRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $type = 'dummy';
        $version = 'dummyVersion';
        $condition = new class () implements ConditionInterface {};
        $transport = new Transport('webhook', "https://localhost/callback", "secret", "session");

        $createEventSubSubscriptionRequest = new CreateEventSubSubscriptionRequest(
            $type,
            $version,
            $condition,
            $transport
        );

        $this->assertSame($type, $createEventSubSubscriptionRequest->getType());
        $this->assertSame($version, $createEventSubSubscriptionRequest->getVersion());
        $this->assertSame($condition, $createEventSubSubscriptionRequest->getCondition());
        $this->assertSame($transport, $createEventSubSubscriptionRequest->getTransport());
    }
}
