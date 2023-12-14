<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Channels;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Channels\ContentClassificationLabel;
use SimplyStream\TwitchApi\Helix\Models\Channels\Label;

class ContentClassificationLabelTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $labelId1 = 'test-id-1';
        $labelIsEnabled1 = true;
        $label1 = new Label($labelId1, $labelIsEnabled1);

        $labelId2 = 'test-id-2';
        $labelIsEnabled2 = false;
        $label2 = new Label($labelId2, $labelIsEnabled2);

        $labels = [$label1, $label2];

        $cls = new ContentClassificationLabel($labels);

        $this->assertEquals($labels, $cls->getContentClassificationLabels());

        $this->assertIsArray($cls->toArray());
        $this->assertSame([
            'content_classification_labels' => [
                [
                    'id' => 'test-id-1',
                    'is_enabled' => true,
                ],
                [
                    'id' => 'test-id-2',
                    'is_enabled' => false,
                ],
            ],
        ], $cls->toArray());
    }
}
