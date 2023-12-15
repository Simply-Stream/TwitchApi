<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\HypeTrain;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\HypeTrain\Contribution;
use SimplyStream\TwitchApi\Helix\Models\HypeTrain\EventData;

final class EventDataTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $contribution = new Contribution(100, 'testType', 'testUser');
        $date = new DateTimeImmutable();

        $eventData = new EventData(
            'testBroadcasterId',
            $date,
            $date,
            50,
            'testId',
            $contribution,
            1,
            $date,
            [$contribution],
            100
        );

        $this->assertInstanceOf(EventData::class, $eventData);
        $this->assertEquals('testBroadcasterId', $eventData->getBroadcasterId());
        $this->assertEquals($date, $eventData->getCooldownEndTime());
        $this->assertEquals($date, $eventData->getExpiresAt());
        $this->assertEquals(50, $eventData->getGoal());
        $this->assertEquals('testId', $eventData->getId());
        $this->assertEquals($contribution, $eventData->getLastContribution());
        $this->assertEquals(1, $eventData->getLevel());
        $this->assertEquals($date, $eventData->getStartedAt());
        $this->assertEquals([$contribution], $eventData->getTopContributions());
        $this->assertEquals(100, $eventData->getTotal());
    }
}
