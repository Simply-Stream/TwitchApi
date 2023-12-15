<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\GuestStar;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\GuestStar\GuestStarInvite;

class GuestStarInviteTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $userId = 'testUser';
        $invitedAt = new DateTimeImmutable();
        $status = 'INVITED';
        $isVideoEnabled = true;
        $isAudioEnabled = true;
        $isVideoAvailable = true;
        $isAudioAvailable = true;

        $guestStarInvite = new GuestStarInvite(
            $userId,
            $invitedAt,
            $status,
            $isVideoEnabled,
            $isAudioEnabled,
            $isVideoAvailable,
            $isAudioAvailable
        );

        $this->assertEquals($userId, $guestStarInvite->getUserId());
        $this->assertEquals($invitedAt, $guestStarInvite->getInvitedAt());
        $this->assertEquals($status, $guestStarInvite->getStatus());
        $this->assertEquals($isVideoEnabled, $guestStarInvite->isVideoEnabled());
        $this->assertEquals($isAudioEnabled, $guestStarInvite->isAudioEnabled());
        $this->assertEquals($isVideoAvailable, $guestStarInvite->isVideoAvailable());
        $this->assertEquals($isAudioAvailable, $guestStarInvite->isAudioAvailable());
    }
}
