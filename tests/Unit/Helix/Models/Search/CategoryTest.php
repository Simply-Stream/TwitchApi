<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Search;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Search\Category;

final class CategoryTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $id = 'testId';
        $name = 'testName';
        $boxArtUrl = 'testUrl';

        $category = new Category($id, $name, $boxArtUrl);

        $this->assertSame($id, $category->getId());
        $this->assertSame($name, $category->getName());
        $this->assertSame($boxArtUrl, $category->getBoxArtUrl());
    }
}
