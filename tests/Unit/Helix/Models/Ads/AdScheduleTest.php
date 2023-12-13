<?php

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Ads;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Ads\AdSchedule;

class AdScheduleTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $snoozeRefreshAt = new \DateTimeImmutable('+5 minutes');
        $nextAdAt = new \DateTimeImmutable('+ 1 hour');
        $lastAdAt = new \DateTimeImmutable('- 1 hour');

        $sut = new AdSchedule(
            1,
            $snoozeRefreshAt,
            $nextAdAt,
            60,
            $lastAdAt,
            0
        );

        $this->assertSame(1, $sut->getSnoozeCount());
        $this->assertSame($snoozeRefreshAt, $sut->getSnoozeRefreshAt());
        $this->assertSame($nextAdAt, $sut->getNextAdAt());
        $this->assertSame(60, $sut->getLengthSeconds());
        $this->assertSame($lastAdAt, $sut->getLastAdAt());
        $this->assertSame(0, $sut->getPrerollFreeTimeSeconds());
    }

    public function testCanBeSerializedToArray()
    {
        $snoozeRefreshAt = new \DateTimeImmutable('+5 minutes');
        $nextAdAt = new \DateTimeImmutable('+ 1 hour');
        $lastAdAt = new \DateTimeImmutable('- 1 hour');

        $sut = new AdSchedule(
            1,
            $snoozeRefreshAt,
            $nextAdAt,
            60,
            $lastAdAt,
            0
        );

        $this->assertIsArray($sut->toArray());
        $this->assertSame([
            'snooze_count' => 1,
            'snooze_refresh_at' => $snoozeRefreshAt->format(DATE_RFC3339_EXTENDED),
            'next_ad_at' => $nextAdAt->format(DATE_RFC3339_EXTENDED),
            'length_seconds' => 60,
            'last_ad_at' => $lastAdAt->format(DATE_RFC3339_EXTENDED),
            'preroll_free_time_seconds' => 0,
        ], $sut->toArray());
    }
}
