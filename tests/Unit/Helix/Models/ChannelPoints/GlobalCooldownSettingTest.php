<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\ChannelPoints;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\GlobalCooldownSetting;

final class GlobalCooldownSettingTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $expectedIsEnabled = true;
        $expectedGlobalCooldownSeconds = 60;

        $globalCooldownSetting = new GlobalCooldownSetting($expectedIsEnabled, $expectedGlobalCooldownSeconds);

        $this->assertEquals($expectedIsEnabled, $globalCooldownSetting->isEnabled());
        $this->assertEquals($expectedGlobalCooldownSeconds, $globalCooldownSetting->getGlobalCooldownSeconds());

        $this->assertIsArray($globalCooldownSetting->toArray());
        $this->assertSame([
            'is_enabled' => $expectedIsEnabled,
            'global_cooldown_seconds' => $expectedGlobalCooldownSeconds,
        ], $globalCooldownSetting->toArray());
    }
}
