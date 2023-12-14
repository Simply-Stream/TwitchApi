<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Clip;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Clip\ClipProcess;

final class ClipProcessTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $editUrl = 'http://example.com/edit';
        $id = 'uniqueId123';

        $clipProcess = new ClipProcess($editUrl, $id);

        $this->assertSame($editUrl, $clipProcess->getEditUrl());
        $this->assertSame($id, $clipProcess->getId());
    }
}
