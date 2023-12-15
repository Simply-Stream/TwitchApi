<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Streams;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Streams\Marker;

final class MarkerTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $id = "1";
        $createdAt = new DateTimeImmutable("now");
        $positionSeconds = 100;
        $description = "Marker description";
        $url = "https://twitch.tv";

        $marker = new Marker(
            $id,
            $createdAt,
            $positionSeconds,
            $description,
            $url
        );

        $this->assertEquals($id, $marker->getId());
        $this->assertEquals($createdAt, $marker->getCreatedAt());
        $this->assertEquals($positionSeconds, $marker->getPositionSeconds());
        $this->assertEquals($description, $marker->getDescription());
        $this->assertEquals($url, $marker->getUrl());
    }
}
