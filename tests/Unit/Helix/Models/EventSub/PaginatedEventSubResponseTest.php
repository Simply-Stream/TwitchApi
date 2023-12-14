<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\EventSub;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\EventSub\PaginatedEventSubResponse;
use SimplyStream\TwitchApi\Helix\Models\Pagination;

class PaginatedEventSubResponseTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $pagination = new Pagination('testCursor');
        $data = ['hello' => 'world'];
        $total = 10;
        $totalCost = 20;
        $maxTotalCost = 30;

        $instance = new PaginatedEventSubResponse(
            $data,
            $pagination,
            $total,
            $totalCost,
            $maxTotalCost
        );

        $this->assertEquals($total, $instance->getTotal());
        $this->assertEquals($totalCost, $instance->getTotalCost());
        $this->assertEquals($maxTotalCost, $instance->getMaxTotalCost());
    }
}
