<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Raids;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Raids\Raid;

class RaidTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $createdAt = new DateTimeImmutable();
        $isMature = true;

        $raid = new Raid($createdAt, $isMature);

        $this->assertSame($createdAt, $raid->getCreatedAt());
        $this->assertSame($isMature, $raid->isMature());
    }
}
