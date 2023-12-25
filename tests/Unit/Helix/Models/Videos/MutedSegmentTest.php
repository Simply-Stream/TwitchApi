<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Videos;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Videos\MutedSegment;

final class MutedSegmentTest extends TestCase
{
    #[DataProvider('provideTestData')]
    public function testCanBeInitialized(int $offset, int $duration)
    {
        $mutedSegment = new MutedSegment($offset, $duration);

        $this->assertEquals($offset, $mutedSegment->getOffset());
        $this->assertEquals($duration, $mutedSegment->getDuration());
    }

    public static function provideTestData()
    {
        return [
            [10, 20],
            [30, 40],
            [50, 60],
        ];
    }
}
