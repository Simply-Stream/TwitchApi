<?php

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Ads;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Ads\Commercial;

class CommercialTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $sut = new Commercial(100, '', 200);

        $this->assertSame(100, $sut->getLength());
        $this->assertEmpty($sut->getMessage());
        $this->assertSame(200, $sut->getRetryAfter());

        $this->assertIsArray($sut->toArray());
        $this->assertSame([
            'length' => 100,
            'message' => '',
            'retry_after' => 200,
        ], $sut->toArray());
    }
}
