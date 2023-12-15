<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Users;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Users\Panel;

final class PanelTest extends TestCase
{
    #[Dataprovider('panelDataProvider')]
    public function testCanBeInitialized(bool $active, ?string $id, ?string $version, ?string $name)
    {
        $panel = new Panel($active, $id, $version, $name);

        $this->assertEquals($active, $panel->isActive());
        $this->assertEquals($id, $panel->getId());
        $this->assertEquals($version, $panel->getVersion());
        $this->assertEquals($name, $panel->getName());
    }

    public static function panelDataProvider()
    {
        return [
            [true, 'testId', 'testVersion', 'testName'],
            [false, 'testId', 'testVersion', 'testName'],
            [true, null, null, null],
        ];
    }
}
