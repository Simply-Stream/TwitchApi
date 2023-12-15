<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Users;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Users\UpdateUserExtension;

class UpdateUserExtensionTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $data = [
            'overlay' => [
                1 => ['active' => true, 'id' => 'extId1', 'version' => '1.1.0'],
                2 => ['active' => false, 'id' => 'extId2', 'version' => '1.2.0'],
            ],
        ];

        $model = new UpdateUserExtension($data);

        $this->assertEquals(
            $data,
            $model->getData(),
            'The __construct method of UpdateUserExtension class did not correctly initialize data.'
        );
    }
}
