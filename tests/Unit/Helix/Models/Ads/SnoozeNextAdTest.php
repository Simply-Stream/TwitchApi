<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Ads;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Ads\SnoozeNextAd;

class SnoozeNextAdTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $nextAdAt = new \DateTimeImmutable('+5 minutes');
        $snoozeRefreshAt = new \DateTimeImmutable();
        $sut = new SnoozeNextAd(10, $snoozeRefreshAt, $nextAdAt);

        $this->assertEquals(10, $sut->getSnoozeCount());
        $this->assertEquals($snoozeRefreshAt, $sut->getSnoozeRefreshAt());
        $this->assertEquals($nextAdAt, $sut->getNextAdAt());

        $this->assertIsArray($sut->toArray());
        $this->assertEquals([
            'snooze_count' => 10,
            'snooze_refresh_at' => $snoozeRefreshAt->format(DATE_RFC3339_EXTENDED),
            'next_ad_at' => $nextAdAt->format(DATE_RFC3339_EXTENDED),
        ], $sut->toArray());
    }
}
