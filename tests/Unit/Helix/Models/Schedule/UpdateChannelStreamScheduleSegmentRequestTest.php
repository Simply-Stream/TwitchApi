<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Schedule;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Schedule\UpdateChannelStreamScheduleSegmentRequest;

class UpdateChannelStreamScheduleSegmentRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $startTime = new DateTimeImmutable();
        $duration = '60';
        $categoryId = 'categoryId';
        $title = 'title';
        $isCanceled = false;
        $timezone = 'timezone';

        $updateChannelStreamScheduleSegmentRequest = new UpdateChannelStreamScheduleSegmentRequest(
            $startTime,
            $duration,
            $categoryId,
            $title,
            $isCanceled,
            $timezone
        );

        $this->assertEquals($startTime, $updateChannelStreamScheduleSegmentRequest->getStartTime());
        $this->assertEquals($duration, $updateChannelStreamScheduleSegmentRequest->getDuration());
        $this->assertEquals($categoryId, $updateChannelStreamScheduleSegmentRequest->getCategoryId());
        $this->assertEquals($title, $updateChannelStreamScheduleSegmentRequest->getTitle());
        $this->assertEquals($isCanceled, $updateChannelStreamScheduleSegmentRequest->getIsCanceled());
        $this->assertEquals($timezone, $updateChannelStreamScheduleSegmentRequest->getTimezone());
    }
}
