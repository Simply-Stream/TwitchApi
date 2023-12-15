<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Streams;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Streams\Stream;

class StreamTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $id = '1';
        $userId = '100';
        $userLogin = 'user1';
        $userName = 'user1';
        $gameId = '2';
        $gameName = 'Super Mario';
        $type = 'live';
        $title = 'My Stream';
        $tags = ['tag1', 'tag2'];
        $viewerCount = 500;
        $startedAt = new DateTimeImmutable();
        $language = 'en';
        $thumbnailUrl = 'thumbnailUrl';
        $isMature = false;

        $stream = new Stream(
            $id,
            $userId,
            $userLogin,
            $userName,
            $gameId,
            $gameName,
            $type,
            $title,
            $tags,
            $viewerCount,
            $startedAt,
            $language,
            $thumbnailUrl,
            $isMature
        );

        $this->assertInstanceOf(Stream::class, $stream);
        $this->assertEquals($id, $stream->getId());
        $this->assertEquals($userId, $stream->getUserId());
        $this->assertEquals($userLogin, $stream->getUserLogin());
        $this->assertEquals($userName, $stream->getUserName());
        $this->assertEquals($gameId, $stream->getGameId());
        $this->assertEquals($gameName, $stream->getGameName());
        $this->assertEquals($type, $stream->getType());
        $this->assertEquals($title, $stream->getTitle());
        $this->assertEquals($tags, $stream->getTags());
        $this->assertEquals($viewerCount, $stream->getViewerCount());
        $this->assertEquals($startedAt, $stream->getStartedAt());
        $this->assertEquals($language, $stream->getLanguage());
        $this->assertEquals($thumbnailUrl, $stream->getThumbnailUrl());
        $this->assertEquals($isMature, $stream->isMature());
    }
}
