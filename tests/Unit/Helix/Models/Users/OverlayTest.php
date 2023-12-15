<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Users;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Users\Overlay;

class OverlayTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $active = true;
        $id = 'test_id';
        $version = 'test_version';
        $name = 'test_name';

        $overlay = new Overlay($active, $id, $version, $name);

        $this->assertEquals($active, $overlay->isActive());
        $this->assertEquals($id, $overlay->getId());
        $this->assertEquals($version, $overlay->getVersion());
        $this->assertEquals($name, $overlay->getName());
    }
}
