<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Streams;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Streams\StreamMarker;
use SimplyStream\TwitchApi\Helix\Models\Streams\Video;

class StreamMarkerTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $userId = 'testUserId';
        $userName = 'testUserName';
        $userLogin = 'testUserLogin';

        $video = new Video(
            'testVideoId',
            []
        );

        $videos = [$video];

        $streamMarker = new StreamMarker(
            $userId,
            $userName,
            $userLogin,
            $videos
        );

        $this->assertEquals($userId, $streamMarker->getUserId());
        $this->assertEquals($userName, $streamMarker->getUserName());
        $this->assertEquals($userLogin, $streamMarker->getUserLogin());
        $this->assertEquals($videos, $streamMarker->getVideos());
    }
}
