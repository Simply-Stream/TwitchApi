<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Chat;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Chat\ChatBadge;
use SimplyStream\TwitchApi\Helix\Models\Chat\Version;

final class ChatBadgeTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $setId = "randomSetId";
        $versions = [
            new Version(
                "id1",
                "imageUrl1x1",
                "imageUrl2x1",
                "imageUrl4x1",
                "title1",
                "description1",
                "clickAction1",
                "clickUrl1"
            ),
            new Version(
                "id2",
                "imageUrl1x2",
                "imageUrl2x2",
                "imageUrl4x2",
                "title2",
                "description2",
                "clickAction2",
                "clickUrl2"
            ),
        ];

        $chatBadge = new ChatBadge($setId, $versions);

        $this->assertSame($setId, $chatBadge->getSetId());
        $this->assertSame($versions, $chatBadge->getVersions());

        $expectedArray = [
            'set_id' => $setId,
            'versions' => array_map(function ($version) {
                return $version->toArray();
            }, $versions),
        ];

        $this->assertEquals($expectedArray, $chatBadge->toArray());
    }
}
