<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\GuestStar;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\GuestStar\MediaSettings;

/**
 * Class MediaSettingsTest
 *
 * Test case for class MediaSettings in SimplyStream\TwitchApi\Helix\Models\GuestStar namespace.
 */
final class MediaSettingsTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $mediaSetting = new MediaSettings(true, false, true);
        $this->assertInstanceOf(MediaSettings::class, $mediaSetting);

        $this->assertEquals(true, $mediaSetting->isHostEnabled());
        $this->assertEquals(false, $mediaSetting->isGuestEnabled());
        $this->assertEquals(true, $mediaSetting->isAvailable());
    }
}
