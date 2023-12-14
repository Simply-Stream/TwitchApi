<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Helix\Models\EventSub;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ConditionInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\MultipleEventResponse;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

class MultipleEventResponseTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $type = 'testType';
        $version = 'testVersion';
        $id = 'testId';
        $status = 'testStatus';

        $condition = new class () implements ConditionInterface {
        };
        $transport = new Transport('webhook', 'callback', 'secret');
        $createdAt = new \DateTimeImmutable();

        $subscription = new Subscription(
            $type,
            $version,
            $condition,
            $transport,
            $id,
            $status,
            $createdAt
        );

        $events = [];
        $challenge = 'testChallenge';

        $multipleEventResponse = new MultipleEventResponse($subscription, $events, $challenge);

        $this->assertSame($subscription, $multipleEventResponse->getSubscription());
        $this->assertSame($events, $multipleEventResponse->getEvents());
        $this->assertSame($challenge, $multipleEventResponse->getChallenge());
    }
}
