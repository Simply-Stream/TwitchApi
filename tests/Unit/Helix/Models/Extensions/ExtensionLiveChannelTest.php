<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Extensions;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Extensions\ExtensionLiveChannel;

class ExtensionLiveChannelTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $broadcasterId = "broadcasterId";
        $broadcasterName = "broadcasterName";
        $gameName = "gameName";
        $gameId = "gameId";
        $title = "streamTitle";

        $extLiveChannel = new ExtensionLiveChannel(
            $broadcasterId,
            $broadcasterName,
            $gameName,
            $gameId,
            $title
        );

        $this->assertSame($broadcasterId, $extLiveChannel->getBroadcasterId());
        $this->assertSame($broadcasterName, $extLiveChannel->getBroadcasterName());
        $this->assertSame($gameName, $extLiveChannel->getGameName());
        $this->assertSame($gameId, $extLiveChannel->getGameId());
        $this->assertSame($title, $extLiveChannel->getTitle());
    }
}
