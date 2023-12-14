<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Analytics;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Analytics\DateRange;
use SimplyStream\TwitchApi\Helix\Models\Analytics\GameAnalytics;

final class GameAnalyticsTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $gameId = '1234';
        $URL = 'http://www.example.com';
        $type = 'TestType';
        $startedAt = new DateTimeImmutable('2022-01-01T00:00:00Z');
        $endedAt = new DateTimeImmutable('2022-01-01T23:59:59Z');
        $dateRange = new DateRange($startedAt, $endedAt);

        $gameAnalytics = new GameAnalytics($gameId, $URL, $type, $dateRange);

        $this->assertSame($gameId, $gameAnalytics->getGameId());
        $this->assertSame($URL, $gameAnalytics->getURL());
        $this->assertSame($type, $gameAnalytics->getType());
        $this->assertSame($dateRange, $gameAnalytics->getDateRange());

        $this->assertIsArray($gameAnalytics->toArray());
        $this->assertEquals([
            'game_id' => $gameId,
            'url' => $URL,
            'type' => $type,
            'date_range' => [
                'started_at' => $startedAt->format(DATE_RFC3339_EXTENDED),
                'ended_at' => $endedAt->format(DATE_RFC3339_EXTENDED),
            ],
        ], $gameAnalytics->toArray());
    }
}
