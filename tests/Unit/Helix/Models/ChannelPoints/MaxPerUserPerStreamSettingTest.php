<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\ChannelPoints;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\MaxPerUserPerStreamSetting;

final class MaxPerUserPerStreamSettingTest extends TestCase
{
    public function testCanBeInitialized(): void
    {
        $setting = new MaxPerUserPerStreamSetting(true, 5);
        $this->assertTrue($setting->isEnabled());
        $this->assertEquals(5, $setting->getMaxPerUserPerStream());

        $this->assertIsArray($setting->toArray());
        $this->assertEquals(['is_enabled' => true, 'max_per_user_per_stream' => 5], $setting->toArray());
    }
}
