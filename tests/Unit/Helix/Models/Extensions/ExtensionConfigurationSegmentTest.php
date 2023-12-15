<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Extensions;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Extensions\ExtensionConfigurationSegment;

class ExtensionConfigurationSegmentTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $segment = 'broadcaster';
        $broadcasterId = '123456';
        $content = '{}';
        $version = '1.0';

        $extensionConfigurationSegment = new ExtensionConfigurationSegment(
            $segment,
            $broadcasterId,
            $content,
            $version
        );

        $this->assertSame($segment, $extensionConfigurationSegment->getSegment());
        $this->assertSame($broadcasterId, $extensionConfigurationSegment->getBroadcasterId());
        $this->assertSame($content, $extensionConfigurationSegment->getContent());
        $this->assertSame($version, $extensionConfigurationSegment->getVersion());
    }
}
