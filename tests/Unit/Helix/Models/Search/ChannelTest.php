<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Search;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Search\Channel;

class ChannelTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $tagArray = ['tag1', 'tag2'];
        $startedAt = new DateTimeImmutable();

        $channel = new Channel(
            'en',
            'loginName',
            'displayName',
            'uniqueID',
            true,
            $tagArray,
            'www.thumbnailurl.com',
            'title',
            $startedAt,
            'gameID',
            'gameName'
        );

        $this->assertEquals('en', $channel->getBroadcasterLanguage());
        $this->assertEquals('loginName', $channel->getBroadcasterLogin());
        $this->assertEquals('displayName', $channel->getDisplayName());
        $this->assertEquals('uniqueID', $channel->getId());
        $this->assertEquals(true, $channel->isLive());
        $this->assertEquals($tagArray, $channel->getTags());
        $this->assertEquals('www.thumbnailurl.com', $channel->getThumbnailUrl());
        $this->assertEquals('title', $channel->getTitle());
        $this->assertEquals($startedAt, $channel->getStartedAt());
        $this->assertEquals('gameID', $channel->getGameId());
        $this->assertEquals('gameName', $channel->getGameName());
    }
}
