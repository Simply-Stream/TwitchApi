<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Analytics;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Analytics\DateRange;

final class DateRangeTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $startedAt = new DateTimeImmutable('2023-01-01 00:00:00');
        $endedAt = new DateTimeImmutable('2023-01-31 23:59:59');

        $sut = new DateRange($startedAt, $endedAt);

        $this->assertInstanceOf(DateRange::class, $sut);
        $this->assertEquals($startedAt, $sut->getStartedAt());
        $this->assertEquals($endedAt, $sut->getEndedAt());

        $this->assertIsArray($sut->toArray());
        $this->assertEquals([
            'started_at' => $startedAt->format(DATE_RFC3339_EXTENDED),
            'ended_at' => $endedAt->format(DATE_RFC3339_EXTENDED),
        ], $sut->toArray());
    }
}
