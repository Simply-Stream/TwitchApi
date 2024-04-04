<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Polls;

use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Polls\Choice;
use SimplyStream\TwitchApi\Helix\Models\Polls\Poll;

final class PollTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $choices = [
            new Choice('1', 'choice1', 10, 5, 2),
            new Choice('2', 'choice2', 20, 10, 4),
        ];

        $poll = new Poll(
            'testId',
            'broadcasterIdTest',
            'broadcasterNameTest',
            'broadcasterLoginTest',
            'pollTitle',
            $choices,
            false,
            0,
            true,
            1,
            'ACTIVE',
            10,
            new DateTimeImmutable(),
            null
        );

        $this->assertSame('testId', $poll->getId());
        $this->assertSame('broadcasterIdTest', $poll->getBroadcasterId());
        $this->assertSame('broadcasterNameTest', $poll->getBroadcasterName());
        $this->assertSame('broadcasterLoginTest', $poll->getBroadcasterLogin());
        $this->assertSame('pollTitle', $poll->getTitle());
        $this->assertSame($choices, $poll->getChoices());
        $this->assertSame(false, $poll->isBitsVotingEnabled());
        $this->assertSame(0, $poll->getBitsPerVote());
        $this->assertSame(true, $poll->isChannelPointsVotingEnabled());
        $this->assertSame(1, $poll->getChannelPointsPerVote());
        $this->assertSame('ACTIVE', $poll->getStatus());
        $this->assertSame(10, $poll->getDuration());
        $this->assertInstanceOf(DateTimeInterface::class, $poll->getStartedAt());
        $this->assertNull($poll->getEndedAt());
    }
}
