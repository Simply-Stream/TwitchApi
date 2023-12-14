<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Chat;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Chat\ChatSettings;

final class ChatSettingsTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $chatSettings = new ChatSettings(
            'broadcasterId1',
            true,
            false,
            60,
            false,
            0,
            true,
            false,
            'moderatorId1',
            null,
            null
        );

        $this->assertInstanceOf(ChatSettings::class, $chatSettings);
        $this->assertSame('broadcasterId1', $chatSettings->getBroadcasterId());
        $this->assertTrue($chatSettings->isEmoteMode());
        $this->assertFalse($chatSettings->isFollowerMode());
        $this->assertSame(60, $chatSettings->getFollowerModeDuration());
        $this->assertFalse($chatSettings->isSlowMode());
        $this->assertSame(0, $chatSettings->getSlowModeWaitTime());
        $this->assertTrue($chatSettings->isSubscriberMode());
        $this->assertFalse($chatSettings->isUniqueChatMode());
        $this->assertSame('moderatorId1', $chatSettings->getModeratorId());
        $this->assertNull($chatSettings->getNonModeratorChatDelay());
        $this->assertNull($chatSettings->getNonModeratorChatDelayDuration());

        $this->assertSame([
            'broadcaster_id' => 'broadcasterId1',
            'emote_mode' => true,
            'follower_mode' => false,
            'follower_mode_duration' => 60,
            'slow_mode' => false,
            'slow_mode_wait_time' => 0,
            'subscriber_mode' => true,
            'unique_chat_mode' => false,
            'moderator_id' => 'moderatorId1',
            'non_moderator_chat_delay' => null,
            'non_moderator_chat_delay_duration' => null
        ], $chatSettings->toArray());
    }
}
