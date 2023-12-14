<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\ChannelPoints;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\MaxPerStreamSetting;

final class MaxPerStreamSettingTest extends TestCase
{
    public function testCanBeInitialized(): void
    {
        $isEnabled = true;
        $maxPerStream = 5;

        $maxPerStreamSetting = new MaxPerStreamSetting($isEnabled, $maxPerStream);

        $this->assertEquals($isEnabled, $maxPerStreamSetting->isEnabled());
        $this->assertEquals($maxPerStream, $maxPerStreamSetting->getMaxPerStream());

        $this->assertIsArray($maxPerStreamSetting->toArray());
        $this->assertEquals([
            'max_per_stream' => $maxPerStream,
            'is_enabled' => $isEnabled,
        ], $maxPerStreamSetting->toArray());
    }
}
