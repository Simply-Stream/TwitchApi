<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\HypeTrain;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\HypeTrain\Contribution;

class ContributionTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $total = 1000;
        $type = 'BITS';
        $user = 'someUser';

        $contribution = new Contribution($total, $type, $user);

        $this->assertEquals($total, $contribution->getTotal(), "Total number of contributions does not match.");
        $this->assertEquals($type, $contribution->getType(), "Type of contribution does not match.");
        $this->assertEquals($user, $contribution->getUser(), "User name does not match.");
    }
}
