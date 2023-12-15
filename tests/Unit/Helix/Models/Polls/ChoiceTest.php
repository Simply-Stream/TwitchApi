<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Polls;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Polls\Choice;

class ChoiceTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $choice = new Choice(
            $id = 'test-id',
            $title = 'test-title',
            $votes = 1,
            $channelPointsVotes = 2,
            $bitsVotes = 0
        );

        self::assertSame($id, $choice->getId());
        self::assertSame($title, $choice->getTitle());
        self::assertSame($votes, $choice->getVotes());
        self::assertSame($channelPointsVotes, $choice->getChannelPointsVotes());
        self::assertSame($bitsVotes, $choice->getBitsVotes());
    }
}
