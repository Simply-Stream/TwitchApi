<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\GuestStar;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\GuestStar\UpdateChannelGuestStarSettingRequest;
use Webmozart\Assert\InvalidArgumentException;

class UpdateChannelGuestStarSettingRequestTest extends TestCase
{
    public function testCanBeInitializedWithNullParameters()
    {
        $request = new UpdateChannelGuestStarSettingRequest();
        $this->assertNull($request->getIsModeratorSendLiveEnabled());
        $this->assertNull($request->getSlotCount());
        $this->assertNull($request->getIsBrowserSourceAudioEnabled());
        $this->assertNull($request->getGroupLayout());
        $this->assertNull($request->getRegenerateBrowserSources());
    }

    public function testCanBeInitializedWithAllParametersProvided()
    {
        $request = new UpdateChannelGuestStarSettingRequest(true, 4, false, 'HORIZONTAL_LAYOUT', true);
        $this->assertTrue($request->getIsModeratorSendLiveEnabled());
        $this->assertEquals(4, $request->getSlotCount());
        $this->assertFalse($request->getIsBrowserSourceAudioEnabled());
        $this->assertEquals('HORIZONTAL_LAYOUT', $request->getGroupLayout());
        $this->assertTrue($request->getRegenerateBrowserSources());
    }

    public function testCannotBeInitializedWithInvalidSlotCount()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Slot count should be less than or equal 6, got 10');

        new UpdateChannelGuestStarSettingRequest(true, 10, false, 'HORIZONTAL_LAYOUT', true);
    }

    public function testCannotBeInitializedWithInvalidGroupLayout()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group layout got an invalid value. Allowed values are: "TILED_LAYOUT", "SCREENSHARE_LAYOUT", "HORIZONTAL_LAYOUT", "VERTICAL_LAYOUT", got "INVALID_LAYOUT"');

        new UpdateChannelGuestStarSettingRequest(true, 4, false, 'INVALID_LAYOUT', true);
    }
}
