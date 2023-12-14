<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\CCLs;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\CCLs\ContentClassificationLabel;

class ContentClassificationLabelTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $sut = new ContentClassificationLabel('1', 'Label Thingy', 'Labelname');

        $this->assertEquals('1', $sut->getId());
        $this->assertEquals('Label Thingy', $sut->getDescription());
        $this->assertEquals('Labelname', $sut->getName());

        $this->assertIsArray($sut->toArray());
        $this->assertEquals([
            'id' => '1',
            'description' => 'Label Thingy',
            'name' => 'Labelname',
        ], $sut->toArray());
    }
}
