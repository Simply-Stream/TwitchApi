<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Streams;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Streams\StreamKey;

class StreamKeyTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $streamKeyString = "testStreamKey";

        $streamKey = new StreamKey($streamKeyString);

        $this->assertInstanceOf(StreamKey::class, $streamKey);
        $this->assertEquals($streamKeyString, $streamKey->getStreamKey());
    }
}
