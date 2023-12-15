<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Schedule;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Schedule\Category;

class CategoryTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $category = new Category('123', 'Just Chatting');

        $this->assertEquals('123', $category->getId());
        $this->assertEquals('Just Chatting', $category->getName());
    }
}
