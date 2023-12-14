<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Channels;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Channels\Label;

class LabelTest extends TestCase
{
    public function testCanBeInitialized(): void
    {
        $id = 'ProfanityVulgarity';
        $isEnabled = true;

        $label = new Label($id, $isEnabled);

        $this->assertSame($id, $label->getId());
        $this->assertSame($isEnabled, $label->isEnabled());

        $this->assertIsArray($label->toArray());
        $expectedArray = [
            'id' => $id,
            'is_enabled' => $isEnabled,
        ];
        $this->assertSame($expectedArray, $label->toArray());
    }
}
