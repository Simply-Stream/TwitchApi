<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Entitlements;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Entitlements\UpdateDropEntitlementRequest;

class UpdateDropEntitlementRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $request = new UpdateDropEntitlementRequest(
            ['testId1', 'testId2'],
            'CLAIMED'
        );
        $this->assertCount(2, $request->getEntitlementIds());
        $this->assertEquals('CLAIMED', $request->getFulfillmentStatus());
    }

    public function testConstructWithMaximumEntitlementIdCount()
    {
        $entitlementIds = array_fill(0, 100, 'testId');
        $request = new UpdateDropEntitlementRequest($entitlementIds, 'FULFILLED');
        $this->assertCount(100, $request->getEntitlementIds());
        $this->assertEquals('FULFILLED', $request->getFulfillmentStatus());
    }

    public function testConstructWithoutArguments()
    {
        $request = new UpdateDropEntitlementRequest();
        $this->assertCount(0, $request->getEntitlementIds());
        $this->assertNull($request->getFulfillmentStatus());
    }

    public function testConstructWithInvalidFulfillmentStatus()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Fulfilment status got an invalid value. Allowed values are: "CLAIMED", "FULFILLED", got "INVALID_STATUS"');

        new UpdateDropEntitlementRequest([], 'INVALID_STATUS');
    }

    public function testConstructWithNonStringEntitlementId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only strings are allowed as entitlement ID');

        new UpdateDropEntitlementRequest([1], 'CLAIMED');
    }

    public function testConstructWithMoreThanMaximumEntitlementIds()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You may specify a maximum of 100 entitlement IDs');

        new UpdateDropEntitlementRequest(array_fill(0, 101, 'testId'), 'FULFILLED');
    }
}
