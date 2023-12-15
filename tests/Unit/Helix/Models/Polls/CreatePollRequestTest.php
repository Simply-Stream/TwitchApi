<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Polls;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Polls\CreatePollRequest;
use Webmozart\Assert\InvalidArgumentException;

class CreatePollRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $broadcasterId = '12345';
        $title = 'What game should I play next?';
        $choices = [['title' => 'Choice 1'], ['title' => 'Choice 2']];
        $duration = 20;
        $channelPointsVotingEnabled = true;
        $channelPointsPerVote = 100;

        $obj = new CreatePollRequest(
            $broadcasterId,
            $title,
            $choices,
            $duration,
            $channelPointsVotingEnabled,
            $channelPointsPerVote
        );

        $this->assertEquals($broadcasterId, $obj->getBroadcasterId());
        $this->assertEquals($title, $obj->getTitle());
        $this->assertEquals($choices, $obj->getChoices());
        $this->assertEquals($duration, $obj->getDuration());
        $this->assertEquals($channelPointsVotingEnabled, $obj->isChannelPointsVotingEnabled());
        $this->assertEquals($channelPointsPerVote, $obj->getChannelPointsPerVote());
    }

    public function testCanBeInitializedEmptyTitle()
    {
        $this->expectException(InvalidArgumentException::class);

        new CreatePollRequest(
            '12345',
            '',
            [['title' => 'Choice 1'], ['title' => 'Choice 2']],
            20,
            true,
            100
        );
    }

    public function testCanBeInitializedInvalidChoices()
    {
        $this->expectException(InvalidArgumentException::class);

        new CreatePollRequest(
            '12345',
            'What game should I play next?',
            [['invalid']],
            20,
            true,
            100
        );
    }

    public function testCanBeInitializedInvalidChoiceTitles()
    {
        $this->expectException(InvalidArgumentException::class);

        new CreatePollRequest(
            '12345',
            'What game should I play next?',
            [['title' => str_repeat('a', 26)]],
            20,
            true,
            100
        );
    }

    public function testCanBeInitializedInvalidDuration()
    {
        $this->expectException(InvalidArgumentException::class);

        new CreatePollRequest(
            '12345',
            'What game should I play next?',
            [['title' => 'Choice 1'], ['title' => 'Choice 2']],
            0,
            true,
            100
        );
    }

    public function testCanBeInitializedInvalidChannelPointsPerVote()
    {
        $this->expectException(InvalidArgumentException::class);

        new CreatePollRequest(
            '12345',
            'What game should I play next?',
            [['title' => 'Choice 1'], ['title' => 'Choice 2']],
            20,
            true,
            0
        );
    }
}
