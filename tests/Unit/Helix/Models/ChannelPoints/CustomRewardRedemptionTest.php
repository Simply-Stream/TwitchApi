<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\ChannelPoints;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\CustomRewardRedemption;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\Reward;

final class CustomRewardRedemptionTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $reward = new Reward('id', 'title', 'prompt', 1000);

        $redeemedAt = new DateTimeImmutable('2022-01-01T00:00:00Z');

        $customRewardRedemption = new CustomRewardRedemption(
            'broadcasterId',
            'broadcasterLogin',
            'broadcasterName',
            'id',
            'userId',
            'userLogin',
            'userName',
            'status',
            $redeemedAt,
            $reward,
            'userInput'
        );

        $this->assertSame('broadcasterId', $customRewardRedemption->getBroadcasterId());
        $this->assertSame('broadcasterLogin', $customRewardRedemption->getBroadcasterLogin());
        $this->assertSame('broadcasterName', $customRewardRedemption->getBroadcasterName());
        $this->assertSame('id', $customRewardRedemption->getId());
        $this->assertSame('userId', $customRewardRedemption->getUserId());
        $this->assertSame('userLogin', $customRewardRedemption->getUserLogin());
        $this->assertSame('userName', $customRewardRedemption->getUserName());
        $this->assertSame('status', $customRewardRedemption->getStatus());
        $this->assertSame('2022-01-01T00:00:00+00:00', $customRewardRedemption->getRedeemedAt()->format('c'));
        $this->assertSame($reward, $customRewardRedemption->getReward());
        $this->assertSame('userInput', $customRewardRedemption->getUserInput());

        $this->assertIsArray($customRewardRedemption->toArray());
        $this->assertSame([
            'broadcaster_id' => 'broadcasterId',
            'broadcaster_login' => 'broadcasterLogin',
            'broadcaster_name' => 'broadcasterName',
            'id' => 'id',
            'user_id' => 'userId',
            'user_login' => 'userLogin',
            'user_name' => 'userName',
            'status' => 'status',
            'redeemed_at' => $redeemedAt->format(DATE_RFC3339_EXTENDED),
            'reward' => [
                'id' => 'id',
                'title' => 'title',
                'prompt' => 'prompt',
                'cost' => 1000,
            ],
            'user_input' => 'userInput',
        ], $customRewardRedemption->toArray());
    }
}
