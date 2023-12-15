<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Schedule;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Schedule\ChannelStreamSchedule;
use SimplyStream\TwitchApi\Helix\Models\Schedule\ScheduleSegment;
use SimplyStream\TwitchApi\Helix\Models\Schedule\Vacation;

class ChannelStreamScheduleTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $segment = new ScheduleSegment(
            'segment-id',
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
            'test stream',
            true,
            null,
            null
        );

        $vacation = new Vacation(
            new \DateTimeImmutable(),
            new \DateTimeImmutable()
        );

        $schedule = new ChannelStreamSchedule(
            [$segment],
            'broadcaster-id',
            'broadcaster-name',
            'broadcaster-login',
            $vacation
        );

        $this->assertIsArray($schedule->getSegments());
        $this->assertSame('broadcaster-id', $schedule->getBroadcasterId());
        $this->assertSame('broadcaster-name', $schedule->getBroadcasterName());
        $this->assertSame('broadcaster-login', $schedule->getBroadcasterLogin());
        $this->assertInstanceOf(Vacation::class, $schedule->getVacation());
    }
}
