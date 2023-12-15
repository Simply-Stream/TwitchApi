<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Schedule;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Schedule\CreateChannelStreamScheduleSegmentRequest;

class CreateChannelStreamScheduleSegmentRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $startTime = new DateTimeImmutable('2023-08-01T12:00:00Z');
        $timezone = 'America/New_York';
        $duration = '120';
        $isRecurring = true;
        $categoryId = 'abc123';
        $title = 'Test Stream Schedule';

        $scheduleRequest = new CreateChannelStreamScheduleSegmentRequest(
            $startTime,
            $timezone,
            $duration,
            $isRecurring,
            $categoryId,
            $title
        );

        $this->assertInstanceOf(CreateChannelStreamScheduleSegmentRequest::class, $scheduleRequest);
        $this->assertEquals($startTime, $scheduleRequest->getStartTime());
        $this->assertEquals($timezone, $scheduleRequest->getTimezone());
        $this->assertEquals($duration, $scheduleRequest->getDuration());
        $this->assertEquals($isRecurring, $scheduleRequest->isRecurring());
        $this->assertEquals($categoryId, $scheduleRequest->getCategoryId());
        $this->assertEquals($title, $scheduleRequest->getTitle());
    }
}
