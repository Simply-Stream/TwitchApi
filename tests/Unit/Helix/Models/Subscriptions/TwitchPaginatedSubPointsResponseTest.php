<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Subscriptions;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Subscriptions\TwitchPaginatedSubPointsResponse;

class TwitchPaginatedSubPointsResponseTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $data = "test_data";
        $points = 42;
        $pagination = new Pagination("test_cursor");
        $total = 10;

        $response = new TwitchPaginatedSubPointsResponse($data, $points, $pagination, $total);

        $this->assertEquals($data, $response->getData());
        $this->assertEquals($points, $response->getPoints());
        $this->assertEquals($pagination, $response->getPagination());
        $this->assertEquals($total, $response->getTotal());
    }

    public function testConstructWithNullTotalAndPagination()
    {
        $data = "test_data";
        $points = 42;

        $response = new TwitchPaginatedSubPointsResponse($data, $points);

        $this->assertEquals($data, $response->getData());
        $this->assertEquals($points, $response->getPoints());
        $this->assertNull($response->getPagination());
        $this->assertNull($response->getTotal());
    }
}
