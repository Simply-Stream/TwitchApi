<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Entitlements;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Entitlements\DropEntitlementUpdate;

class DropEntitlementUpdateTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $status = 'SUCCESS';
        $ids = ['id1', 'id2'];

        $dropEntitlementUpdate = new DropEntitlementUpdate($status, $ids);

        $this->assertInstanceOf(DropEntitlementUpdate::class, $dropEntitlementUpdate);

        $this->assertEquals($status, $dropEntitlementUpdate->getStatus());
        $this->assertEquals($ids, $dropEntitlementUpdate->getIds());
    }
}
