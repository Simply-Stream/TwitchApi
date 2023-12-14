<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Entitlements;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Entitlements\DropEntitlement;

final class DropEntitlementTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $dropEntitlement = new DropEntitlement(
            'testId',
            'testBenefitId',
            new DateTimeImmutable(),
            'testUserId',
            'testGameId',
            'CLAIMED',
            new DateTimeImmutable()
        );

        $this->assertEquals('testId', $dropEntitlement->getId());
        $this->assertEquals('testBenefitId', $dropEntitlement->getBenefitId());
        $this->assertInstanceOf(DateTimeImmutable::class, $dropEntitlement->getTimestamp());
        $this->assertEquals('testUserId', $dropEntitlement->getUserId());
        $this->assertEquals('testGameId', $dropEntitlement->getGameId());
        $this->assertEquals('CLAIMED', $dropEntitlement->getFulfillmentStatus());
        $this->assertInstanceOf(DateTimeImmutable::class, $dropEntitlement->getLastUpdated());
    }
}
