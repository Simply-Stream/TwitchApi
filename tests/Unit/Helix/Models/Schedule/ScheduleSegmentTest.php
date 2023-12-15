<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Schedule;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Schedule\Category;
use SimplyStream\TwitchApi\Helix\Models\Schedule\ScheduleSegment;

final class ScheduleSegmentTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $id = '01234';
        $startTime = new DateTimeImmutable();
        $endTime = $startTime->add(new \DateInterval('PT1H'));
        $title = 'Test Title';
        $isRecurring = true;
        $category = new Category('5678', 'TestCategory');
        $canceledUntil = $startTime->add(new \DateInterval('P3D'));

        $scheduleSegment = new ScheduleSegment(
            $id,
            $startTime,
            $endTime,
            $title,
            $isRecurring,
            $category,
            $canceledUntil
        );

        $this->assertEquals($id, $scheduleSegment->getId());
        $this->assertEquals($startTime, $scheduleSegment->getStartTime());
        $this->assertEquals($endTime, $scheduleSegment->getEndTime());
        $this->assertEquals($title, $scheduleSegment->getTitle());
        $this->assertEquals($isRecurring, $scheduleSegment->isRecurring());
        $this->assertEquals($category, $scheduleSegment->getCategory());
        $this->assertEquals($canceledUntil, $scheduleSegment->getCanceledUntil());
    }
}
