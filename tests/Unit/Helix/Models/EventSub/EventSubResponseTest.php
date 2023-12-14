<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\EventSub;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\EventSub\EventSubResponse;

final class EventSubResponseTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testCanBeInitialized(mixed $data, int $total, int $totalCost, int $maxTotalCost): void
    {
        $response = new EventSubResponse($data, $total, $totalCost, $maxTotalCost);

        $this->assertSame($data, $response->getData());
        $this->assertSame($total, $response->getTotal());
        $this->assertSame($totalCost, $response->getTotalCost());
        $this->assertSame($maxTotalCost, $response->getMaxTotalCost());
    }

    public static function provider(): array
    {
        return [
            [['username' => 'test'], 2, 5, 10],
            [['email' => 'test@test.com'], 3, 7, 15],
        ];
    }
}
