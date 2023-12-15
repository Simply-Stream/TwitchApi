<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Games;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Games\Game;

final class GameTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $id = 'sample-id';
        $name = 'sample-name';
        $boxArtUrl = 'sample-url';
        $igdbId = 'sample-igdbId';

        $game = new Game($id, $name, $boxArtUrl, $igdbId);

        $this->assertSame($id, $game->getId());
        $this->assertSame($name, $game->getName());
        $this->assertSame($boxArtUrl, $game->getBoxArtUrl());
        $this->assertSame($igdbId, $game->getIgdbId());
    }
}
