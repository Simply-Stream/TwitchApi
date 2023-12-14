<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Analytics;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Analytics\DateRange;
use SimplyStream\TwitchApi\Helix\Models\Analytics\ExtensionAnalytics;

final class ExtensionAnalyticsTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $extensionId = 'extension_1';
        $URL = 'https://download/report1';
        $type = 'type1';
        $dateRange = new DateRange(new \DateTimeImmutable('2022-01-01'), new \DateTimeImmutable('2022-01-31'));

        $extensionAnalytics = new ExtensionAnalytics($extensionId, $URL, $type, $dateRange);

        $this->assertSame($extensionId, $extensionAnalytics->getExtensionId());
        $this->assertSame($URL, $extensionAnalytics->getURL());
        $this->assertSame($type, $extensionAnalytics->getType());
        $this->assertEquals($dateRange, $extensionAnalytics->getDateRange());

        $this->assertIsArray($extensionAnalytics->toArray());
        $this->assertEquals([
            'extension_id' => $extensionId,
            'url' => $URL,
            'type' => $type,
            'date_range' => [
                'started_at' => $dateRange->getStartedAt()->format(DATE_RFC3339_EXTENDED),
                'ended_at' => $dateRange->getEndedAt()->format(DATE_RFC3339_EXTENDED),
            ],
        ], $extensionAnalytics->toArray());
    }
}
