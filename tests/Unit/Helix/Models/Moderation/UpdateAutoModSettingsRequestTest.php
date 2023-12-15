<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Moderation;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Moderation\UpdateAutoModSettingsRequest;

class UpdateAutoModSettingsRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $model = new UpdateAutoModSettingsRequest(
            1,  // aggression
            2,  // bullying
            3,  // disability
            4,  // misogyny
            0,  // raceEthnicityOrReligion
            1,  // sexBasedTerms
            2,  // sexualitySexOrGender
            3,  // swearing
            4   // overallLevel
        );

        $this->assertSame(1, $model->getAggression());
        $this->assertSame(2, $model->getBullying());
        $this->assertSame(3, $model->getDisability());
        $this->assertSame(4, $model->getMisogyny());
        $this->assertSame(0, $model->getRaceEthnicityOrReligion());
        $this->assertSame(1, $model->getSexBasedTerms());
        $this->assertSame(2, $model->getSexualitySexOrGender());
        $this->assertSame(3, $model->getSwearing());
        $this->assertSame(4, $model->getOverallLevel());
    }

    public function testCanBeInitializedInvalidValues()
    {
        $this->expectException(\InvalidArgumentException::class);

        $model = new UpdateAutoModSettingsRequest(-1, 0, 1, 2, 3, 4, 5, 6, 7);
    }
}
