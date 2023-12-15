<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Goals;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Goals\CreatorGoal;

final class CreatorGoalTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $id = "TestId";
        $broadcasterId = "TestBroadcasterId";
        $broadcasterName = "TestBroadcasterName";
        $broadcasterLogin = "TestBroadcasterLogin";
        $type = "follower";
        $description = "TestDescription";
        $currentAmount = 10;
        $targetAmount = 100;
        $createdAt = new DateTimeImmutable();

        $instance = new CreatorGoal(
            $id,
            $broadcasterId,
            $broadcasterName,
            $broadcasterLogin,
            $type,
            $description,
            $currentAmount,
            $targetAmount,
            $createdAt
        );

        $this->assertEquals($id, $instance->getId());
        $this->assertEquals($broadcasterId, $instance->getBroadcasterId());
        $this->assertEquals($broadcasterName, $instance->getBroadcasterName());
        $this->assertEquals($broadcasterLogin, $instance->getBroadcasterLogin());
        $this->assertEquals($type, $instance->getType());
        $this->assertEquals($description, $instance->getDescription());
        $this->assertEquals($currentAmount, $instance->getCurrentAmount());
        $this->assertEquals($targetAmount, $instance->getTargetAmount());
        $this->assertEquals($createdAt, $instance->getCreatedAt());
    }
}
