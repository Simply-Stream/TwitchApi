<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Users;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Users\UserExtension;

class UserExtensionTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $id = "TestID";
        $version = "v1.0";
        $name = "TestExtension";
        $canActivate = true;
        $type = ['component', 'overlay'];

        $userExtension = new UserExtension($id, $version, $name, $canActivate, $type);

        $this->assertEquals($id, $userExtension->getId());
        $this->assertEquals($version, $userExtension->getVersion());
        $this->assertEquals($name, $userExtension->getName());
        $this->assertEquals($canActivate, $userExtension->canActivate());
        $this->assertEquals($type, $userExtension->getType());
    }
}
