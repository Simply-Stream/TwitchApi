<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Users;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Users\Component;

class ComponentTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $active = true;
        $id = 'some_id';
        $version = '1.0.0';
        $name = 'ComponentName';
        $x = 5;
        $y = 7;

        $component = new Component($active, $id, $version, $name, $x, $y);

        $this->assertSame($active, $component->isActive());
        $this->assertSame($id, $component->getId());
        $this->assertSame($version, $component->getVersion());
        $this->assertSame($name, $component->getName());
        $this->assertSame($x, $component->getX());
        $this->assertSame($y, $component->getY());
    }
}
