<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Helix\Models\EventSub;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ConditionInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\EventResponse;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\EventInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

class EventResponseTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $condition = new class () implements ConditionInterface {
        };
        $transport = new Transport('webhook', 'callback', 'secret');
        $event = new class () implements EventInterface {
            public const AVAILABLE_EVENTS = [];
        };

        $subscription = new Subscription(
            'type',
            'version',
            $condition,
            $transport,
            'id',
            'status',
            new DateTimeImmutable()
        );

        $eventResponse = new EventResponse($subscription, $event, "challenge");

        $this->assertSame($subscription, $eventResponse->getSubscription());
        $this->assertSame($event, $eventResponse->getEvent());
        $this->assertSame("challenge", $eventResponse->getChallenge());
    }
}
