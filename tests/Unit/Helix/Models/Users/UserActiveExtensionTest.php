<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Users;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Users\Component;
use SimplyStream\TwitchApi\Helix\Models\Users\Overlay;
use SimplyStream\TwitchApi\Helix\Models\Users\Panel;
use SimplyStream\TwitchApi\Helix\Models\Users\UserActiveExtension;

class UserActiveExtensionTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $panels = [new Panel(true, '1', '1.0', 'Panel Name')];
        $overlays = [new Overlay(true, '2', '1.1', 'Overlay Name')];
        $components = [new Component(true, '3', '1.2', 'Component Name', 100, 200)];

        $userActiveExtension = new UserActiveExtension($panels, $overlays, $components);

        $this->assertEquals($panels, $userActiveExtension->getPanel());
        $this->assertEquals($overlays, $userActiveExtension->getOverlay());
        $this->assertEquals($components, $userActiveExtension->getComponent());
    }

    public function testConstructEmptyArrays()
    {
        $panels = [];
        $overlays = [];
        $components = [];

        $userActiveExtension = new UserActiveExtension($panels, $overlays, $components);

        $this->assertEquals($panels, $userActiveExtension->getPanel());
        $this->assertEquals($overlays, $userActiveExtension->getOverlay());
        $this->assertEquals($components, $userActiveExtension->getComponent());
    }
}
