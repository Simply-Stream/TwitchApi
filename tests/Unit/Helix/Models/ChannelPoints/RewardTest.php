<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\ChannelPoints;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\Reward;

/**
 * @covers \SimplyStream\TwitchApi\Helix\Models\ChannelPoints\Reward
 */
final class RewardTest extends TestCase
{
    /**
     * @covers \SimplyStream\TwitchApi\Helix\Models\ChannelPoints\Reward::__construct
     */
    public function testConstruct(): void
    {
        $id = 'testId';
        $title = 'testTitle';
        $prompt = 'testPrompt';
        $cost = 100;

        $reward = new Reward($id, $title, $prompt, $cost);

        $this->assertSame($id, $reward->getId());
        $this->assertSame($title, $reward->getTitle());
        $this->assertSame($prompt, $reward->getPrompt());
        $this->assertSame($cost, $reward->getCost());

        $this->assertIsArray($reward->toArray());
        $this->assertSame([
            'id' => $id,
            'title' => $title,
            'prompt' => $prompt,
            'cost' => $cost,
        ], $reward->toArray());
    }
}
