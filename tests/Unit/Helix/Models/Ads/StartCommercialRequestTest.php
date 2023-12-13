<?php

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Ads;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Ads\StartCommercialRequest;
use Webmozart\Assert\InvalidArgumentException;

class StartCommercialRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $sut = new StartCommercialRequest('12345', 100);

        $this->assertSame('12345', $sut->getBroadcasterId());
        $this->assertSame(100, $sut->getLength());

        $this->assertIsArray($sut->toArray());
        $this->assertSame([
            'broadcaster_id' => '12345',
            'length' => 100,
        ], $sut->toArray());
    }

    public function testBroadcasterIdMustBeSet()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Broadcaster ID can\'t be empty');

        new StartCommercialRequest('', 100);
    }

    public function testCommercialHasMinimumLength()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A commercial should at least be 1 second long. Got "0"');

        new StartCommercialRequest('12345', 0);
    }

    public function testCommercialHasMaximumLength()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The maximum commercial length you should request is 180 seconds. Got "200"');

        new StartCommercialRequest('12345', 200);
    }
}
