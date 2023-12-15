<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Moderation;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Moderation\AutoModSettings;

final class AutoModSettingsTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $broadcasterId = '12345';
        $moderatorId = '67890';
        $overallLevel = 2;
        $disability = 1;
        $aggression = 3;
        $sexualitySexOrGender = 2;
        $misogyny = 1;
        $bullying = 3;
        $swearing = 2;
        $raceEthnicityOrReligion = 1;
        $sexBasedTerms = 2;

        $settings = new AutoModSettings(
            $broadcasterId,
            $moderatorId,
            $overallLevel,
            $disability,
            $aggression,
            $sexualitySexOrGender,
            $misogyny,
            $bullying,
            $swearing,
            $raceEthnicityOrReligion,
            $sexBasedTerms
        );

        $this->assertEquals($broadcasterId, $settings->getBroadcasterId());
        $this->assertEquals($moderatorId, $settings->getModeratorId());
        $this->assertEquals($overallLevel, $settings->getOverallLevel());
        $this->assertEquals($disability, $settings->getDisability());
        $this->assertEquals($aggression, $settings->getAggression());
        $this->assertEquals($sexualitySexOrGender, $settings->getSexualitySexOrGender());
        $this->assertEquals($misogyny, $settings->getMisogyny());
        $this->assertEquals($bullying, $settings->getBullying());
        $this->assertEquals($swearing, $settings->getSwearing());
        $this->assertEquals($raceEthnicityOrReligion, $settings->getRaceEthnicityOrReligion());
        $this->assertEquals($sexBasedTerms, $settings->getSexBasedTerms());
    }
}
