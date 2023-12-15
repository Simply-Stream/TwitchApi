<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Schedule;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Schedule\Vacation;

final class VacationTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $startTime = new \DateTimeImmutable('2018-11-23 06:00:00');
        $endTime = new \DateTimeImmutable('2018-11-24 06:00:00');
        $vacation = new Vacation($startTime, $endTime);

        $this->assertEquals($startTime, $vacation->getStartTime());
        $this->assertEquals($endTime, $vacation->getEndTime());
    }
}
