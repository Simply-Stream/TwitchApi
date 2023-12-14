<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\ChannelPoints;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\CustomReward;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\GlobalCooldownSetting;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\MaxPerStreamSetting;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\MaxPerUserPerStreamSetting;

final class CustomRewardTest extends TestCase
{
    public function testCanBeInitialized(): void
    {
        $maxPerStreamSetting = new MaxPerStreamSetting(true, 5);
        $maxPerUserPerStreamSetting = new MaxPerUserPerStreamSetting(true, 5);
        $globalCooldownSetting = new GlobalCooldownSetting(true, 60);
        $cooldownExpiresAt = new DateTimeImmutable();

        $reward = new CustomReward(
            'broadcasterId',
            'broadcasterLogin',
            'broadcasterName',
            'id',
            'title',
            'prompt',
            100,
            [],
            'backgroundColor',
            true,
            true,
            $maxPerStreamSetting,
            $maxPerUserPerStreamSetting,
            $globalCooldownSetting,
            true,
            true,
            true,
            50,
            ['image'],
            $cooldownExpiresAt
        );

        $this->assertSame('broadcasterId', $reward->getBroadcasterId());
        $this->assertSame('broadcasterLogin', $reward->getBroadcasterLogin());
        $this->assertSame('broadcasterName', $reward->getBroadcasterName());
        $this->assertSame('id', $reward->getId());
        $this->assertSame('title', $reward->getTitle());
        $this->assertSame('prompt', $reward->getPrompt());
        $this->assertSame(100, $reward->getCost());
        $this->assertSame([], $reward->getDefaultImage());
        $this->assertSame('backgroundColor', $reward->getBackgroundColor());
        $this->assertTrue($reward->isEnabled());
        $this->assertTrue($reward->isUserInputRequired());
        $this->assertSame($maxPerStreamSetting, $reward->getMaxPerStreamSetting());
        $this->assertSame($maxPerUserPerStreamSetting, $reward->getMaxPerUserPerStreamSetting());
        $this->assertSame($globalCooldownSetting, $reward->getGlobalCooldownSetting());
        $this->assertTrue($reward->isPaused());
        $this->assertTrue($reward->isInStock());
        $this->assertTrue($reward->isShouldRedemptionsSkipRequestQueue());
        $this->assertSame(50, $reward->getRedemptionsRedeemedCurrentStream());
        $this->assertSame(['image'], $reward->getImage());
        $this->assertSame($cooldownExpiresAt, $reward->getCooldownExpiresAt());

        $this->assertIsArray($reward->toArray());
        $this->assertSame([
            'broadcaster_id' => 'broadcasterId',
            'broadcaster_login' => 'broadcasterLogin',
            'broadcaster_name' => 'broadcasterName',
            'id' => 'id',
            'title' => 'title',
            'prompt' => 'prompt',
            'cost' => 100,
            'default_image' => [],
            'background_color' => 'backgroundColor',
            'is_enabled' => true,
            'is_user_input_required' => true,
            'max_per_stream_setting' => [
                'is_enabled' => true,
                'max_per_stream' => 5,
            ],
            'max_per_user_per_stream_setting' => [
                'is_enabled' => true,
                'max_per_user_per_stream' => 5,
            ],
            'global_cooldown_setting' => [
                'is_enabled' => true,
                'global_cooldown_seconds' => 60,
            ],
            'is_paused' => true,
            'is_in_stock' => true,
            'should_redemptions_skip_request_queue' => true,
            'redemptions_redeemed_current_stream' => 50,
            'image' => [
                0 => 'image',
            ],
            'cooldown_expires_at' => $cooldownExpiresAt->format(DATE_RFC3339_EXTENDED),
        ], $reward->toArray());
    }
}
