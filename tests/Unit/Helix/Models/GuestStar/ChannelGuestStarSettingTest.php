<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\GuestStar;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\GuestStar\ChannelGuestStarSetting;

final class ChannelGuestStarSettingTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $moderatorLiveEnabled = true;
        $slotCount = 3;
        $browserAudioEnabled = false;
        $groupLayout = 'TILED_LAYOUT';
        $browserSourceToken = 'token1234';

        $channelGuestStarSetting = new ChannelGuestStarSetting(
            $moderatorLiveEnabled,
            $slotCount,
            $browserAudioEnabled,
            $groupLayout,
            $browserSourceToken
        );

        $this->assertSame($moderatorLiveEnabled, $channelGuestStarSetting->isModeratorSendLiveEnabled());
        $this->assertSame($slotCount, $channelGuestStarSetting->getSlotCount());
        $this->assertSame($browserAudioEnabled, $channelGuestStarSetting->isBrowserSourceAudioEnabled());
        $this->assertSame($groupLayout, $channelGuestStarSetting->getGroupLayout());
        $this->assertSame($browserSourceToken, $channelGuestStarSetting->getBrowserSourceToken());
    }
}
