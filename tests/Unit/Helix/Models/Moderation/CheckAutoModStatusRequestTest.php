<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Moderation;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Moderation\CheckAutoModStatus;
use SimplyStream\TwitchApi\Helix\Models\Moderation\CheckAutoModStatusRequest;

final class CheckAutoModStatusRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $entries = array_fill(0, 99, new CheckAutoModStatus("testMsgId", "testMsgText"));

        $model = new CheckAutoModStatusRequest($entries);
        $this->assertEquals($entries, $model->getData());
    }

    public function testLessThan100Entries()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a value less than or equal to 100. Got: 101');

        $entries = array_fill(0, 101, new CheckAutoModStatus("testMsgId", "testMsgText"));

        $model = new CheckAutoModStatusRequest($entries);

        $this->assertEquals($entries, $model->getData());
    }
}
