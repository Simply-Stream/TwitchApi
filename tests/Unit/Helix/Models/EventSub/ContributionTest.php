<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\EventSub;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Contribution;

class ContributionTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $contribution = new Contribution(
            total: 1000,
            type: 'bits',
            userId: '12345',
            userLogin: 'testUser',
            userName: 'Test User'
        );

        $this->assertEquals(1000, $contribution->getTotal());
        $this->assertEquals('bits', $contribution->getType());
        $this->assertEquals('12345', $contribution->getUserId());
        $this->assertEquals('testUser', $contribution->getUserLogin());
        $this->assertEquals('Test User', $contribution->getUserName());
    }
}
