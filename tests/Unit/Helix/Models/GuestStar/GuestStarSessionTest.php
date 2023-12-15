<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\GuestStar;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\GuestStar\GuestStarSession;
use SimplyStream\TwitchApi\Helix\Models\GuestStar\MediaSettings;

final class GuestStarSessionTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $id = "unique_id";
        $guests = ["guest1", "guest2", "guest3"];
        $slotId = "0";
        $isLive = true;
        $userId = "user_id";
        $userDisplayName = "user_name";
        $userLogin = "user_login";
        $volume = 80;
        $assignedAt = new DateTimeImmutable();
        $audioSettings = new MediaSettings(true, true, true);
        $videoSettings = new MediaSettings(true, true, true);

        $guestStarSession = new GuestStarSession(
            $id,
            $guests,
            $slotId,
            $isLive,
            $userId,
            $userDisplayName,
            $userLogin,
            $volume,
            $assignedAt,
            $audioSettings,
            $videoSettings
        );

        $this->assertSame($id, $guestStarSession->getId());
        $this->assertSame($guests, $guestStarSession->getGuests());
        $this->assertSame($slotId, $guestStarSession->getSlotId());
        $this->assertSame($isLive, $guestStarSession->isLive());
        $this->assertSame($userId, $guestStarSession->getUserId());
        $this->assertSame($userDisplayName, $guestStarSession->getUserDisplayName());
        $this->assertSame($userLogin, $guestStarSession->getUserLogin());
        $this->assertSame($volume, $guestStarSession->getVolume());
        $this->assertSame($assignedAt, $guestStarSession->getAssignedAt());
        $this->assertSame($audioSettings, $guestStarSession->getAudioSettings());
        $this->assertSame($videoSettings, $guestStarSession->getVideoSettings());
    }
}
