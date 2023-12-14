<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Chat;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Chat\UpdateChatSettingsRequest;
use Webmozart\Assert\InvalidArgumentException;

final class UpdateChatSettingsRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $request = new UpdateChatSettingsRequest(
            true,
            true,
            30,
            true,
            4,
            true,
            30,
            true,
            true
        );

        $this->assertEquals(true, $request->getEmoteMode());
        $this->assertEquals(true, $request->getFollowerMode());
        $this->assertEquals(30, $request->getFollowerModeDuration());
        $this->assertEquals(true, $request->getNonModeratorChatDelay());
        $this->assertEquals(4, $request->getNonModeratorChatDelayDuration());
        $this->assertEquals(true, $request->getSlowMode());
        $this->assertEquals(30, $request->getSlowModeWaitTime());
        $this->assertEquals(true, $request->getSubscriberMode());
        $this->assertEquals(true, $request->getUniqueChatMode());
    }

    public function testFollowerModeCannotExceedLimit(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Follower mode duration can't exceed 3 months (129600 seconds). Got 129601");

        new UpdateChatSettingsRequest(
            null,
            true,
            129601,
            true,
            4,
            true,
            30,
            false,
            true
        );
    }

    public function testInvalidNonModeratorChatDelayDuration(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid non moderator chat delay duration. Allowed values: 2, 4, 6, got 7');

        new UpdateChatSettingsRequest(
            null,
            true,
            30,
            true,
            7,
            true,
            30,
            true,
            false
        );
    }

    public function testSlowModeValidations(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Slow mode maximum value is 120 seconds, got 122');

        new UpdateChatSettingsRequest(
            null,
            true,
            30,
            false,
            6,
            true,
            122,
            true,
            true
        );
    }
}
