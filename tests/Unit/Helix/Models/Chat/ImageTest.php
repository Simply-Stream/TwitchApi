<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Chat;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Chat\Image;

class ImageTest extends TestCase
{
    public function testCanBeInitialized(): void
    {
        $url1x = 'http://example.com/small.jpg';
        $url2x = 'http://example.com/medium.jpg';
        $url4x = 'http://example.com/large.jpg';

        $image = new Image($url1x, $url2x, $url4x);

        $this->assertEquals($url1x, $image->getUrl1x());
        $this->assertEquals($url2x, $image->getUrl2x());
        $this->assertEquals($url4x, $image->getUrl4x());
    }
}
